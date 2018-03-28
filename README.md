# 文档
> **写在前面**
> 1. 安装命令`composer require doing/phpqrcode 版本号` ，例 `composer require doing/phpqrcode 1.*`(如果安装不是最新或者没找到包可能是中国镜像有延迟)
> 2. 此包可以集成任何框架:所以PHP都能集成(本包作者在ThinkPHP5中编写，故在此框架下集成可能会有更好的体验)
> 3. 此包依赖于`phpqrcode`在此感谢为此奉献的作者们
> 4. [packagist地址](https://packagist.org/packages/doing/phpqrcode)
> 5. [github地址](https://github.com/Doing0/phpqrcode)欢迎`Star`

## 简单调用示例
>  注：不会自动创建文件夹(存放二维码的)如果文件夹不存在会报错,以下为例就得先创建文件夹uploads

```php
//二维码的内容
 $text = "Test";
//二维码导出的储存地址
 $outfile = "uploads/222.png";
//二维码的大小
 $size = 6;
 //调用方法成功后,会在相应文件夹下生成二维码文件
 QRcode::png($text, $outfile, $size);
```
### 上述中 png方法一共7个参数(建议只填$text,$outfile)
> 在传递以下参数时一定注意顺序和默认值

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

## VCARD名片信息二维码
~~~
$content = 'BEGIN:VCARD' . "\n";
$content .= 'VERSION:2.1' . "\n";
$content .= 'N:张' . "\n";
$content .= 'FN:三' . "\n";
$content .= 'TEL;WORK;VOICE:18780808080' . "\n";
$content .= 'ORG:公司:xxx科技有限公司'. "\n";
$content .= 'END:VCARD' . "\n";
$file = 'zs.png';
//调用方法成功后,会在相应文件夹下生成二维码文件
 QRcode::png($content, $file);
 
 当扫码此二维码时,点击保存联系人就会把联系人的姓+名 电话 公司信息等保存在联系人里面
 请求参数$content的编写规则可去百度搜索vcard参数

~~~




