<?php
header('Content-type: text/html; charset=UTF-8');
require './config.php';
require './common_action.php';
require './sql_action.php';

if (!isset($_POST['TradeInfo'])) {
    header("Location:https://thor-exhaust.tw/?status=payerror");
} else {
    $TradeInfo = $_POST['TradeInfo'];
    $data      = CommonAction::createNewebpayDecrypt($TradeInfo);
    $data      = json_decode($data, true);
    $result    = $data['Result'];
    $userInfo  = SqlAction::getorder($result['MerchantOrderNo']);
}

if ($result['PaymentType'] == 'VACC' || $result['PaymentType'] == 'CVS' || $result['PaymentType'] == 'BARCODE') {
    if ($result['PaymentType'] == 'VACC') {
        $payWay    = 'ATM 轉帳';
        $bankCode  = $result['BankCode'];
        $codeNo    = $result['CodeNo'];
        $barCode1  = '';
        $barCode2  = '';
        $barCode3  = '';
        $putCodeNo = '銀行代號：' . $bankCode . '<br />' . '銀行帳號：' . $codeNo;
        $ifBarCode = 'none';
    }
    if ($result['PaymentType'] == 'CVS') {
        $payWay    = '超商代碼繳費';
        $barCode1  = '';
        $barCode2  = '';
        $barCode3  = '';
        $putCodeNo = $result['CodeNo'];
        $ifBarCode = 'none';
    }
    if ($result['PaymentType'] == 'BARCODE') {
        $payWay    = '超商條碼繳費';
        $barCode1  = $result['Barcode_1'];
        $barCode2  = $result['Barcode_2'];
        $barCode3  = $result['Barcode_3'];
        $putCodeNo = '';
        $ifBarCode = 'block';
    }

    ##################SendMailToCustom##################
    //載入PHPMAILER套件
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'PHPMailer/src/Exception.php';

    //讀取模板
    $pagecontents = file_get_contents("./blade/AfterSendOrder.html");
    //更改模板資訊
    $Body = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
    $Body = str_replace("PayWay", $userInfo['payway'], $Body);
    $Body = str_replace("IfBarCode", $ifBarCode, $Body);
    $Body = str_replace("BarCode1", $barCode1, $Body);
    $Body = str_replace("BarCode2", $barCode2, $Body);
    $Body = str_replace("BarCode3", $barCode3, $Body);
    $Body = str_replace("PutCodeNo", $putCodeNo, $Body);
    $Body = str_replace("OrderAmt", $result['Amt'], $Body);
    $Body = str_replace("ExpireDate", $result['ExpireDate'], $Body);
    $Body = str_replace("OrderNo", $result['MerchantOrderNo'], $Body);
    $Body = str_replace("ProductName", $userInfo['product_name'], $Body);
    $Body = str_replace("OrderModel", $userInfo['model'], $Body);
    $Body = str_replace("FrontOption", $userInfo['front_option'], $Body);
    $Body = str_replace("PowerOption", $userInfo['power_option'], $Body);
    $Body = str_replace("CaseOption", $userInfo['case_option'], $Body);
    $Body = str_replace("UserName", $userInfo['name'], $Body);
    $Body = str_replace("FacebookName", $userInfo['facebook_name'], $Body);
    $Body = str_replace("UserAddress", $userInfo['address'], $Body);
    $Body = str_replace("UserEmail", $userInfo['email'], $Body);
    $Body = str_replace("UserPhone", $userInfo['phone'], $Body);
    $Body = str_replace("UserCommit", $userInfo['commit'], $Body);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->IsSMTP(); //設定使用SMTP方式寄信
    $mail->SMTPAuth   = true; //設定SMTP需要驗證
    $mail->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
    $mail->Host       = 'smtp.gmail.com'; //Gamil的SMTP主機
    $mail->Port       = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
    $mail->CharSet    = 'utf-8'; //郵件編碼
    $mail->Username   = 'thorexhaust1999@gmail.com'; //Gamil帳號
    $mail->Password   = 'wfzfgcczsyeangei'; //Gmail密碼
    $mail->From       = 'thorexhaust1999@gmail.com'; //寄件者信箱
    $mail->FromName   = '雷神排氣管-支援中心'; //寄件者姓名
    $mail->Subject    = '【雷神排氣管】感謝您的訂購，我們將盡快聯絡您：'; //郵件標題
    $mail->Body       = $Body;
    $mail->IsHTML(true); //郵件內容為html ( true || false)
    $mail->AddAddress($userInfo['email']); //收件者郵件及名稱

    ##################SendMailToUs##################

    //讀取模板
    $pagecontents = file_get_contents("./blade/AfterSendOrder_us.html");
    //更改模板資訊
    $Body_us = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
    $Body_us = str_replace("TitleUserName", $userInfo['name'], $Body_us);
    $Body_us = str_replace("PayWay", $payWay, $Body_us);
    $Body_us = str_replace("IfBarCode", $ifBarCode, $Body_us);
    $Body_us = str_replace("BarCode1", $barCode1, $Body_us);
    $Body_us = str_replace("BarCode2", $barCode2, $Body_us);
    $Body_us = str_replace("BarCode3", $barCode3, $Body_us);
    $Body_us = str_replace("CVSCodeNo", $putCodeNo, $Body_us);
    $Body_us = str_replace("OrderAmt", $result['Amt'], $Body_us);
    $Body_us = str_replace("ExpireDate", $result['ExpireDate'], $Body_us);
    $Body_us = str_replace("OrderNo", $result['MerchantOrderNo'], $Body_us);
    $Body_us = str_replace("ProductName", $userInfo['product_name'], $Body_us);
    $Body_us = str_replace("OrderModel", $userInfo['model'], $Body_us);
    $Body_us = str_replace("FrontOption", $userInfo['front_option'], $Body_us);
    $Body_us = str_replace("PowerOption", $userInfo['power_option'], $Body_us);
    $Body_us = str_replace("CaseOption", $userInfo['case_option'], $Body_us);
    $Body_us = str_replace("UserName", $userInfo['name'], $Body_us);
    $Body_us = str_replace("FacebookName", $userInfo['facebook_name'], $Body_us);
    $Body_us = str_replace("UserAddress", $userInfo['address'], $Body_us);
    $Body_us = str_replace("UserEmail", $userInfo['email'], $Body_us);
    $Body_us = str_replace("UserPhone", $userInfo['phone'], $Body_us);
    $Body_us = str_replace("UserCommit", $userInfo['commit'], $Body_us);

    $mail_us = new PHPMailer\PHPMailer\PHPMailer();
    $mail_us->IsSMTP(); //設定使用SMTP方式寄信
    $mail_us->SMTPAuth   = true; //設定SMTP需要驗證
    $mail_us->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
    $mail_us->Host       = 'smtp.gmail.com'; //Gamil的SMTP主機
    $mail_us->Port       = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
    $mail_us->CharSet    = 'utf-8'; //郵件編碼
    $mail_us->Username   = 'thorexhaust1999@gmail.com'; //Gamil帳號
    $mail_us->Password   = 'wfzfgcczsyeangei'; //Gmail密碼
    $mail_us->From       = 'thorexhaust1999@gmail.com'; //寄件者信箱
    $mail_us->FromName   = '雷神排氣管-銷售中心'; //寄件者姓名
    $mail_us->Subject    = '【雷神排氣管】新訂單，您有一筆來自客戶' . $userInfo['name'] . '的訂單：'; //郵件標題
    $mail_us->Body       = $Body_us;
    $mail_us->IsHTML(true); //郵件內容為html ( true || false)
    $mail_us->AddAddress('thorexhaust1999@gmail.com'); //收件者郵件及名稱

    if ($mail->Send() && $mail_us->Send()) {
        header("Location:https://thor-exhaust.tw/?status=order_finish&order_no=" . $result['MerchantOrderNo']);
        exit;
    } else {
        header("Location:https://thor-exhaust.tw/?status=payerror");
        exit;
    }
} elseif ($result['PaymentType'] == 'CREDIT' || $result['PaymentType'] == 'WEBATM') {
    header("Location:https://thor-exhaust.tw/?status=order_finish&order_no=" . $result['MerchantOrderNo']);
    exit;
} else {
    header("Location:https://thor-exhaust.tw/?status=payerror");
}
