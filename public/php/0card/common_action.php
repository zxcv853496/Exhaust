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
     * 0卡解密資料
     * @return string
     */
    public static function createZeroCardDecrypt($encrypt = "")
    {
        $app_cc_aes_iv = 'AMM5123456789012';
        $app_cc_aes_key = 'AMM51234567890123456789012345678';
        $data = openssl_decrypt($encrypt, "aes-256-cbc", $app_cc_aes_key, 0, $app_cc_aes_iv);
        return $data;
    }

}