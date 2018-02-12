# phpqrcode
## 调用说明
>  注：不会自动创建文件夹如果文件夹不存在会报错,以下为例就得先创建文件夹uploads
```
 $text = "Test";
 $outfile = "uploads/222.png";
 $size = 6;
 //调用方法成功后,会在相应文件夹下生成二维码文件
 QRcode::png($text, $outfile, $size);
```

## png方法一共7个参数(建议只填$text,$outfile)
1. $text是二维码的内容;
2. $outfile导出文件的路径和文件名称:后缀一定是png,测试时建议不要路径只要文件名如：2222.png,生成了图片后去盘符搜索此文件在哪个文件夹里面,然后根据此文件的路径设计$outfile的路径。
3. $size图片大小默认是6建议不用传:可根据业务需求调整:数字越大图片越大
4. $color二维码样式:默认是黑色16进制#000,建议不要修改
5. $level是容错率模式是最高:强烈建议不修改
6. $margin=2二维码外面的白边距离越大留白越大,强烈建议不修改
7. $saveandprint是否打印强烈建议不修改

## 给二维码加头像或者logo
 ```
 $QR调用png生成的二维码全路径
 $QR = 'D:\phpStudy\WWW\credits\public\uploads\2.png';
 $header头像全路径:头像一定是正方形
 $header = 'D:\phpStudy\WWW\credits\public\uploads\1.png';
 $QR = QRcode::addHeader($header, $QR);
 //调用addHeader后,之前的二维码被覆盖,生成合并后二维码,并返回全路径$QR
 ```
