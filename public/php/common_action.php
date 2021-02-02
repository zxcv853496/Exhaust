<?php
require './config.php';

class CommonAction
{

    /**
     * 產生隨機字串
     * @return string
     */
    public static function createRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        if (!is_int($length) || $length < 0) {
            return false;
        }
        $characters_length = strlen($characters) - 1;
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $characters_length)];
        }
        return $string;
    }

    /**
     * 產生回傳訊息
     * @return string
     */
    public static function returnMsg($status, $msg, $token)
    {
        $return_data = (object) array();
        $return_data->status = $status;
        $return_data->msg = $msg;
        $return_data->token = $token;
        $return_data->data = (object) array();
        return json_encode($return_data);
    }

    /**
     * 產生藍新加密資料
     * @return string
     */
    public static function createNewebpayEncrypt($parameter = "")
    {
        global $NewebpayConfig;
        $return_str = '';
        if (!empty($parameter)) {
            $return_str = http_build_query($parameter);
        }

        return trim(bin2hex(openssl_encrypt(self::addpadding($return_str), 'aes-256-cbc', $NewebpayConfig['HashKey'], OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $NewebpayConfig['HashIV'])));
    }
    public static function addPadding($string, $blocksize = 32)
    {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }

    /**
     * 產生藍新解密資料
     * @return string
     */
    public static function createNewebpayDecrypt($parameter = "")
    {
        global $NewebpayConfig;
        return self::strippadding(openssl_decrypt(hex2bin($parameter), 'AES-256-CBC', $NewebpayConfig['HashKey'], OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $NewebpayConfig['HashIV']));
    }
    public static function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

}