<?php
/**
 * QRcode.php
 *
 * Created by arielferrandini
 */
namespace PHPQRCode;
use Exception;

class QRcode {

    public $version;
    public $width;
    public $data;

    //----------------------------------------------------------------------
    public function encodeMask(QRinput $input, $mask)
    {
        if($input->getVersion() < 0 || $input->getVersion() > Constants::QRSPEC_VERSION_MAX) {
            throw new Exception('wrong version');
        }
        if($input->getErrorCorrectionLevel() > Constants::QR_ECLEVEL_H) {
            throw new Exception('wrong level');
        }

        $raw = new QRrawcode($input);

        QRtools::markTime('after_raw');

        $version = $raw->version;
        $width = QRspec::getWidth($version);
        $frame = QRspec::newFrame($version);

        $filler = new FrameFiller($width, $frame);
        if(is_null($filler)) {
            return NULL;
        }

        // inteleaved data and ecc codes
        for($i=0; $i<$raw->dataLength + $raw->eccLength; $i++) {
            $code = $raw->getCode();
            $bit = 0x80;
            for($j=0; $j<8; $j++) {
                $addr = $filler->next();
                $filler->setFrameAt($addr, 0x02 | (($bit & $code) != 0));
                $bit = $bit >> 1;
            }
        }

        QRtools::markTime('after_filler');

        unset($raw);

        // remainder bits
        $j = QRspec::getRemainder($version);
        for($i=0; $i<$j; $i++) {
            $addr = $filler->next();
            $filler->setFrameAt($addr, 0x02);
        }

        $frame = $filler->frame;
        unset($filler);


        // masking
        $maskObj = new QRmask();
        if($mask < 0) {

            if (Constants::QR_FIND_BEST_MASK) {
                $masked = $maskObj->mask($width, $frame, $input->getErrorCorrectionLevel());
            } else {
                $masked = $maskObj->makeMask($width, $frame, (intval(Constants::QR_DEFAULT_MASK) % 8), $input->getErrorCorrectionLevel());
            }
        } else {
            $masked = $maskObj->makeMask($width, $frame, $mask, $input->getErrorCorrectionLevel());
        }

        if($masked == NULL) {
            return NULL;
        }

        QRtools::markTime('after_mask');

        $this->version = $version;
        $this->width = $width;
        $this->data = $masked;

        return $this;
    }

    //----------------------------------------------------------------------
    public function encodeInput(QRinput $input)
    {
        return $this->encodeMask($input, -1);
    }

    //----------------------------------------------------------------------
    public function encodeString8bit($string, $version, $level)
    {
        if(string == NULL) {
            throw new Exception('empty string!');
            return NULL;
        }

        $input = new QRinput($version, $level);
        if($input == NULL) return NULL;

        $ret = $input->append($input, Constants::QR_MODE_8, strlen($string), str_split($string));
        if($ret < 0) {
            unset($input);
            return NULL;
        }
        return $this->encodeInput($input);
    }

    //----------------------------------------------------------------------
    public function encodeString($string, $version, $level, $hint, $casesensitive)
    {

        if($hint != Constants::QR_MODE_8 && $hint != Constants::QR_MODE_KANJI) {
            throw new Exception('bad hint');
            return NULL;
        }

        $input = new QRinput($version, $level);
        if($input == NULL) return NULL;

        $ret = QRsplit::splitStringToQRinput($string, $input, $hint, $casesensitive);
        if($ret < 0) {
            return NULL;
        }

        return $this->encodeInput($input);
    }

    //----------------------------------------------------------------------
    public static function png($text, $outfile = false,$size = 6, $color="#000", $level = Constants::QR_ECLEVEL_H, $margin = 2, $saveandprint=false)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encodePNG($text, $outfile, $saveandprint=false,$color);
    }

    //----------------------------------------------------------------------
    public static function text($text, $outfile = false, $level = Constants::QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encode($text, $outfile);
    }

    //----------------------------------------------------------------------
    public static function raw($text, $outfile = false, $level = Constants::QR_ECLEVEL_L, $size = 3, $margin = 4)
    {
        $enc = QRencode::factory($level, $size, $margin);
        return $enc->encodeRAW($text, $outfile);
    }

    /** 简述:生成的二维码添加头像或者logo并返回路径
     *
     * @params
     *
     */
    public static function addHeader($header,$QR)
    {
        # 二维码资源
        $QR_Obj = imagecreatefromstring(file_get_contents($QR));
        #头像资源
        $header_Obj = imagecreatefromstring(file_get_contents($header));
        //二维码图片宽度
        $QR_width = imagesx($QR_Obj);
        //二维码图片高度
        $QR_height = imagesy($QR_Obj);
        //添加图片宽度
        $header_width = imagesx($header_Obj);
        //添加图片高度
        $header_height = imagesy($header_Obj);

        $logo_qr_width = $QR_width / 5;
        $scale = $header_width/$logo_qr_width;
        $logo_qr_height = $header_height/$scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        //重新组合图片并调整大小
        imagecopyresampled($QR_Obj, $header_Obj, $from_width, $from_width, 0, 0, $logo_qr_width,
                           $logo_qr_height, $header_width, $header_height);
        if (imagepng($QR_Obj, $QR) !==true) throw new Exception('服务器繁忙');
        return $QR;
    }//pf
}//class

