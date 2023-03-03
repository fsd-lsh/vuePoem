<?php

class v_code
{
    private $charset = "abcdefghjklmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    private $code;
    private $codelen = 4;
    private $width = 80;
    private $height = 30;
    private $img;
    private $font;
    private $fontsize = 16;
    private $fontcolor;

    public function __construct()
    {
        $this->font = realpath('../../') . '/' . SYS_ENV['FONTS_PATH'] . '/font.ttf';
    }

    private function createCode()
    {
        $_leng = strlen($this->charset) - 1;
        for ($i = 1; $i <= $this->codelen; $i++) {
            $this->code .= $this->charset[mt_rand(0, $_leng)];
        }
        session('verify_code', strtolower($this->code));
        return $this->code;
    }

    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    private function createFont()
    {
        $_x = ($this->width / $this->codelen);
        for ($i = 0; $i < $this->codelen; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imagettftext($this->img, $this->fontsize, mt_rand(-30, 30), $_x * $i + mt_rand(1, 5), $this->height / 1.4, $color, $this->font, $this->code[$i]);
        }
    }

    private function createLine()
    {
        for ($i = 0; $i < 6; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        for ($i = 0; $i < 45; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(220, 255), mt_rand(220, 255), mt_rand(220, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    public function doimg()
    {
        //加载背景
        $this->createBg();
        //加载文件
        $this->createCode();
        //加载线条
        $this->createLine();
        //加载字体
        $this->createFont();
        //加载背景
        $this->outPut();
    }

    public function getCode()
    {
        return strtolower($this->code);
    }

    public function checkCode($code, $clear = false)
    {
        if (session('verify_code') == strtolower($code)) {
            if($clear) $this->clearCode();
            return true;
        }
        if($clear) $this->clearCode();
        return false;
    }

    public function clearCode()
    {
        session('verify_code', NULL);
    }
}
