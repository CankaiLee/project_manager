<?php

if ( ! function_exists('generate_code') )
{
    /**
     * 返回指定长度的随机字符串
     *
     * @param int $length
     * @return string
     */
    function generate_code($length = 8)
    {
        $code = '';
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";
        $app_key = config('app.app_key');

        $i = 0;
        while ($i++ < $length) {
            $code .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }

        return strtoupper(hash('adler32', $app_key . $code));
    }
}
