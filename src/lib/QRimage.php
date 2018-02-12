<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

namespace PHPQRCode;

class QRimage {

    //----------------------------------------------------------------------
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveandprint = FALSE,$color)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame,$color);

        if ($filename === false)
        {
            Header("Content-type: image/png");
            ImagePng($image);
        }else
        {
            if ($saveandprint === TRUE)
            {
                ImagePng($image, $filename);
                header("Content-type: image/png");
                ImagePng($image);
            }else
            {
                ImagePng($image, $filename);
            }
        }

        ImageDestroy($image);
    }

    //----------------------------------------------------------------------
    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $q = 85)
    {
        $image = self::image($frame, $pixelPerPoint, $outerFrame);

        if ($filename === false)
        {
            Header("Content-type: image/jpeg");
            ImageJpeg($image, null, $q);
        }else
        {
            ImageJpeg($image, $filename, $q);
        }

        ImageDestroy($image);
    }

    //----------------------------------------------------------------------
    private static function image($frame, $pixelPerPoint = 4, $outerFrame = 4,$color )
    {
        $h = count($frame);
        $w = strlen($frame[0]);

        $imgW = $w + 2 * $outerFrame;
        $imgH = $h + 2 * $outerFrame;

        $base_image = ImageCreate($imgW, $imgH);

        $col[0] = ImageColorAllocate($base_image, 255, 255, 255);
        $colorArray = self::hex2rgb($color);
        $col[1] = ImageColorAllocate($base_image, $colorArray['red'], $colorArray['green'],
                                     $colorArray['blue']);

        imagefill($base_image, 0, 0, $col[0]);

        for ($y = 0; $y < $h; $y++)
        {
            for ($x = 0; $x < $w; $x++)
            {
                if ($frame[$y][$x] == '1')
                {
                    ImageSetPixel($base_image, $x + $outerFrame, $y + $outerFrame, $col[1]);
                }
            }
        }

        $target_image = ImageCreate($imgW * $pixelPerPoint, $imgH * $pixelPerPoint);
        ImageCopyResized($target_image, $base_image, 0, 0, 0, 0, $imgW * $pixelPerPoint, $imgH * $pixelPerPoint, $imgW, $imgH);
        ImageDestroy($base_image);

        return $target_image;
    }

   private static function  hex2rgb($colour)
    {
        if ($colour[0] == '#')
        {
            $colour = substr($colour, 1);
        }
        if (strlen($colour) == 6)
        {
            list($r, $g, $b) = [$colour[0] . $colour[1], $colour[2] .
                $colour[3], $colour[4] . $colour[5]];
        }elseif (strlen($colour) == 3)
        {
            list($r, $g, $b) = [$colour[0] . $colour[0], $colour[1] .
                $colour[1], $colour[2] . $colour[2]];
        }else
        {
            return false;
        }
        $r = hexdec($r);
        $g = hexdec($g);
        $b = hexdec($b);
        return ['red' => $r, 'green' => $g, 'blue' => $b];
    }
}