<?php
header("Content-Type: text/html; charset=utf-8");
require __DIR__ . '/config.php';

if (!class_exists('PHPMailer\PHPMailer\Exception')) {
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require '../PHPMailer/src/Exception.php';
}

class MailAction
{

    /**
     * 訂單成立信件
     * @return string
     */
    public static function OrderCreate($order_no)
    {

        set_error_handler(function ($code, $msg, $file, $line, $context) {
            $error_log = 'Error[' . $code . ']: ' . $msg . ' - Place： ' . $file . ' (' . $line . ')';
            error_log($error_log . PHP_EOL, 3, __DIR__ . '/php-error.log');
            die;
        });
        //取得訂單資料
        $order_data = SqlAction::GetOrder($order_no);
        //取得零卡訂單資料
        $zero_card_data = SqlAction::GetZeroCardOrder($order_no);
        //Mail給客戶
        $title = '【牛王排氣管】已收到您的零卡分期訂單，目前狀態：';
        self::SendToCustomer($order_data, $zero_card_data, $title);
        //Mail給我們
        self::SendToOfficial($order_data, $zero_card_data);
    }

    /**
     * 訂單更新信件
     * @return string
     */
    public static function OrderUpdate($order_no)
    {

        set_error_handler(function ($code, $msg, $file, $line, $context) {
            $error_log = 'Error[' . $code . ']: ' . $msg . ' - Place： ' . $file . ' (' . $line . ')';
            error_log($error_log . PHP_EOL, 3, __DIR__ . '/php-error.log');
            die;
        });
        //取得訂單資料
        $order_data = SqlAction::GetOrder($order_no);
        //取得零卡訂單資料
        $zero_card_data = SqlAction::GetZeroCardOrder($order_no);
        //Mail給客戶
        $title = '【牛王排氣管】您的零卡分期訂單狀態已更新：';
        self::SendToCustomer($order_data, $zero_card_data, $title);
        //Mail給我們
        self::SendToOfficial($order_data, $zero_card_data);
    }

    /**
     * 寄信給客戶
     * @return string
     */
    public static function SendToCustomer($order_data, $zero_card_data, $title)
    {

        set_error_handler(function ($code, $msg, $file, $line, $context) {
            $error_log = 'Error[' . $code . ']: ' . $msg . ' - Place： ' . $file . ' (' . $line . ')';
            error_log($error_log . PHP_EOL, 3, __DIR__ . '/php-error.log');
            die;
        });
        //引入PHPMAILER
        //Mail給客戶

        $transaction_state_array = [
            "消費者尚未在 payment_url 上進行操作" => "https://i.imgur.com/yKW3sAu.gif",
            "此交易已轉專人審核處理中" => "https://i.imgur.com/yKW3sAu.gif",
            "交易已核准但尚未請款" => "https://i.imgur.com/Zc37oTb.gif",
            "交易請款中" => "https://i.imgur.com/Zc37oTb.gif",
            "交易已撥款" => "https://i.imgur.com/Zc37oTb.gif",
            "交易失敗(婉拒)" => "https://i.imgur.com/URs9y1q.gif",
            "交易在核准後通知取消或已全額退款" => "https://i.imgur.com/URs9y1q.gif",
            "訂單在審核時取消或逾時取消" => "https://i.imgur.com/URs9y1q.gif",
            "部份取消資料處理中" => "https://i.imgur.com/URs9y1q.gif",
        ];

        $mail_img = $transaction_state_array[$zero_card_data['transaction_state']];

        if ($zero_card_data['transaction_state'] == '交易已核准但尚未請款') {
            $zero_card_data['transaction_state'] = '交易已核准';
        }

        //讀取模板
        $pagecontents = file_get_contents("../blade/0CardAfterPay.html");
        //更改模板資訊
        $Body = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
        $Body = str_replace("OrderNo", $order_data['order_no'], $Body);
        $Body = str_replace("ProductName", $order_data['product_name'], $Body);
        $Body = str_replace("OrderModel", $order_data['model'], $Body);
        $Body = str_replace("FrontOption", $order_data['front_option'], $Body);
        $Body = str_replace("PowerOption", $order_data['power_option'], $Body);
        $Body = str_replace("CaseOption", $order_data['case_option'], $Body);
        $Body = str_replace("PaymentType", $order_data['payway'], $Body);
        $Body = str_replace("PayTime", date("Y-m-d"), $Body);
        $Body = str_replace("OrderAmt", $order_data['total_price'], $Body);
        $Body = str_replace("UserName", $order_data['name'], $Body);
        $Body = str_replace("UserAddress", $order_data['address'], $Body);
        $Body = str_replace("UserEmail", $order_data['email'], $Body);
        $Body = str_replace("UserPhone", $order_data['phone'], $Body);
        $Body = str_replace("UserCommit", $order_data['commit'], $Body);
        $Body = str_replace("MainMessage", "感謝您在牛王排氣管官方網站訂購的商品，我們已經收到了您的0卡分期訂單", $Body);
        $Body = str_replace("SubMessage", "目前狀態：" . $zero_card_data['transaction_state'], $Body);
        $Body = str_replace("StatusImg", $mail_img, $Body);

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP(); //設定使用SMTP方式寄信
        $mail->SMTPAuth = true; //設定SMTP需要驗證
        $mail->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
        $mail->Host = 'smtp.gmail.com'; //Gamil的SMTP主機
        $mail->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
        $mail->CharSet = 'utf-8'; //郵件編碼
        $mail->Username = 'bullking.website@gmail.com'; //Gamil帳號
        $mail->Password = '.gc39Ab,3^BQ:fe'; //Gmail密碼
        $mail->From = 'bullking.website@gmail.com'; //寄件者信箱
        $mail->FromName = '牛王排氣管-銷售中心'; //寄件者姓名
        $mail->Subject = $title . $zero_card_data['transaction_state']; //郵件標題
        $mail->Body = $Body;
        $mail->IsHTML(true); //郵件內容為html ( true || false)
        $mail->AddAddress($order_data['email']); //收件者郵件及名稱
        $mail->Send();
    }

    /**
     * 寄信給我們
     * @return string
     */
    public static function SendToOfficial($order_data, $zero_card_data)
    {

        set_error_handler(function ($code, $msg, $file, $line, $context) {
            $error_log = 'Error[' . $code . ']: ' . $msg . ' - Place： ' . $file . ' (' . $line . ')';
            error_log($error_log . PHP_EOL, 3, __DIR__ . '/php-error.log');
            die;
        });
        //引入PHPMAILER
        //Mail給我們
        $transaction_state_array = [
            "消費者尚未在 payment_url 上進行操作" => "https://i.imgur.com/yKW3sAu.gif",
            "此交易已轉專人審核處理中" => "https://i.imgur.com/yKW3sAu.gif",
            "交易已核准但尚未請款" => "https://i.imgur.com/Zc37oTb.gif",
            "交易請款中" => "https://i.imgur.com/Zc37oTb.gif",
            "交易已撥款" => "https://i.imgur.com/Zc37oTb.gif",
            "交易失敗(婉拒)" => "https://i.imgur.com/URs9y1q.gif",
            "交易在核准後通知取消或已全額退款" => "https://i.imgur.com/URs9y1q.gif",
            "訂單在審核時取消或逾時取消" => "https://i.imgur.com/URs9y1q.gif",
            "部份取消資料處理中" => "https://i.imgur.com/URs9y1q.gif",
        ];

        $mail_img = $transaction_state_array[$zero_card_data['transaction_state']];

        //讀取模板
        $pagecontents = file_get_contents("../blade/0CardAfterPay_us.html");
        //更改模板資訊
        $Body_us = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
        $Body_us = str_replace("TitleUserName", $order_data['name'], $Body_us);
        $Body_us = str_replace("OrderNo", $order_data['order_no'], $Body_us);
        $Body_us = str_replace("ProductName", $order_data['product_name'], $Body_us);
        $Body_us = str_replace("OrderModel", $order_data['model'], $Body_us);
        $Body_us = str_replace("FrontOption", $order_data['front_option'], $Body_us);
        $Body_us = str_replace("PowerOption", $order_data['power_option'], $Body_us);
        $Body_us = str_replace("CaseOption", $order_data['case_option'], $Body_us);
        $Body_us = str_replace("PaymentType", $order_data['payway'], $Body_us);
        $Body_us = str_replace("PayTime", date("Y-m-d"), $Body_us);
        $Body_us = str_replace("OrderAmt", $order_data['total_price'], $Body_us);
        $Body_us = str_replace("UserName", $order_data['name'], $Body_us);
        $Body_us = str_replace("UserAddress", $order_data['address'], $Body_us);
        $Body_us = str_replace("UserEmail", $order_data['email'], $Body_us);
        $Body_us = str_replace("UserPhone", $order_data['phone'], $Body_us);
        $Body_us = str_replace("UserCommit", $order_data['commit'], $Body_us);
        $Body_us = str_replace("MainMessage", "零卡分期訂單", $Body_us);
        $Body_us = str_replace("NowState", $zero_card_data['transaction_state'], $Body_us);
        $Body_us = str_replace("StatusImg", $mail_img, $Body_us);

        $mail_us = new PHPMailer\PHPMailer\PHPMailer();
        $mail_us->IsSMTP(); //設定使用SMTP方式寄信
        $mail_us->SMTPAuth = true; //設定SMTP需要驗證
        $mail_us->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
        $mail_us->Host = 'smtp.gmail.com'; //Gamil的SMTP主機
        $mail_us->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
        $mail_us->CharSet = 'utf-8'; //郵件編碼
        $mail_us->Username = 'bullking.website@gmail.com'; //Gamil帳號
        $mail_us->Password = '.gc39Ab,3^BQ:fe'; //Gmail密碼
        $mail_us->From = 'bullking.website@gmail.com'; //寄件者信箱
        $mail_us->FromName = '牛王排氣管-銷售中心'; //寄件者姓名
        $mail_us->Subject = '【牛王排氣管】客戶' . $order_data['name'] . '的零卡分期訂單，狀態：' . $zero_card_data['transaction_state']; //郵件標題
        $mail_us->Body = $Body_us;
        $mail_us->IsHTML(true); //郵件內容為html ( true || false)
        $mail_us->AddAddress('yongxin19861986@gmail.com'); //收件者郵件及名稱
        $mail_us->Send();
    }

}