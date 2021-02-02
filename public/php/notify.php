<?php
header('Content-type: text/html; charset=UTF-8');
require './config.php';
require './common_action.php';
require './sql_action.php';

$TradeInfo = $_POST['TradeInfo'];
SqlAction::LogNotify(json_encode($TradeInfo), "");
$data = CommonAction::createNewebpayDecrypt($TradeInfo);
$data = json_decode($data, true);

$result = $data['Result'];
$userInfo = SqlAction::getorder($result['MerchantOrderNo']);
SqlAction::updateOrderPay($result['MerchantOrderNo']);

##################SendMailToCustom##################
//載入PHPMAILER套件
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

// //讀取模板
$pagecontents = file_get_contents("./blade/AfterPay.html");
//更改模板資訊
$Body = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
$Body = str_replace("OrderNo", $result['MerchantOrderNo'], $Body);
$Body = str_replace("ProductName", $userInfo['product_name'], $Body);
$Body = str_replace("OrderModel", $userInfo['model'], $Body);
$Body = str_replace("FrontOption", $userInfo['front_option'], $Body);
$Body = str_replace("PowerOption", $userInfo['power_option'], $Body);
$Body = str_replace("CaseOption", $userInfo['case_option'], $Body);
$Body = str_replace("PaymentType", $userInfo['payway'], $Body);
$Body = str_replace("PayTime", $result['PayTime'], $Body);
$Body = str_replace("OrderAmt", $result['Amt'], $Body);
$Body = str_replace("UserName", $userInfo['name'], $Body);
$Body = str_replace("UserAddress", $userInfo['address'], $Body);
$Body = str_replace("UserEmail", $userInfo['email'], $Body);
$Body = str_replace("UserPhone", $userInfo['phone'], $Body);
$Body = str_replace("UserCommit", $userInfo['commit'], $Body);
$Body = str_replace("MainMessage", "感謝您在雷神排氣管官方網站訂購的商品，我們已經收到了您的款項", $Body);
$Body = str_replace("SubMessage", "等待我們進一步確認您的訂單資訊後將會立刻安排商品的製作", $Body);
//https://i.imgur.com/Zc37oTb.gif

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP(); //設定使用SMTP方式寄信
$mail->SMTPAuth = true; //設定SMTP需要驗證
$mail->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
$mail->Host = 'smtp.gmail.com'; //Gamil的SMTP主機
$mail->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail->CharSet = 'utf-8'; //郵件編碼
$mail->Username = 'thor.exhaust.website@gmail.com'; //Gamil帳號
$mail->Password = 'aB:yx+>2RWJbCrw8ohcn'; //Gmail密碼
$mail->From = 'thor.exhaust.website@gmail.com'; //寄件者信箱
$mail->FromName = '雷神排氣管-銷售中心'; //寄件者姓名
$mail->Subject = '【雷神排氣管】已收到您的款項，近期將有專人與您聯絡：'; //郵件標題
$mail->Body = $Body;
$mail->IsHTML(true); //郵件內容為html ( true || false)
$mail->AddAddress($userInfo['email']); //收件者郵件及名稱

// ##################SendMailToUs##################

//讀取模板
$pagecontents = file_get_contents("./blade/AfterPay_us.html");
//更改模板資訊
$Body_us = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
$Body_us = str_replace("TitleUserName", $userInfo['name'], $Body_us);
$Body_us = str_replace("OrderNo", $result['MerchantOrderNo'], $Body_us);
$Body_us = str_replace("ProductName", $userInfo['product_name'], $Body_us);
$Body_us = str_replace("OrderModel", $userInfo['model'], $Body_us);
$Body_us = str_replace("FrontOption", $userInfo['front_option'], $Body_us);
$Body_us = str_replace("PowerOption", $userInfo['power_option'], $Body_us);
$Body_us = str_replace("CaseOption", $userInfo['case_option'], $Body_us);
$Body_us = str_replace("PaymentType", $userInfo['payway'], $Body_us);
$Body_us = str_replace("PayTime", $result['PayTime'], $Body_us);
$Body_us = str_replace("OrderAmt", $result['Amt'], $Body_us);
$Body_us = str_replace("UserName", $userInfo['name'], $Body_us);
$Body_us = str_replace("UserAddress", $userInfo['address'], $Body_us);
$Body_us = str_replace("UserEmail", $userInfo['email'], $Body_us);
$Body_us = str_replace("UserPhone", $userInfo['phone'], $Body_us);
$Body_us = str_replace("UserCommit", $userInfo['commit'], $Body_us);
$Body_us = str_replace("MainMessage", "款項", $Body_us);
//https://i.imgur.com/Zc37oTb.gif

$mail_us = new PHPMailer\PHPMailer\PHPMailer();
$mail_us->IsSMTP(); //設定使用SMTP方式寄信
$mail_us->SMTPAuth = true; //設定SMTP需要驗證
$mail_us->SMTPSecure = 'ssl'; // Gmail的SMTP主機需要使用SSL連線
$mail_us->Host = 'smtp.gmail.com'; //Gamil的SMTP主機
$mail_us->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
$mail_us->CharSet = 'utf-8'; //郵件編碼
$mail_us->Username = 'thor.exhaust.website@gmail.com'; //Gamil帳號
$mail_us->Password = 'aB:yx+>2RWJbCrw8ohcn'; //Gmail密碼
$mail_us->From = 'thor.exhaust.website@gmail.com'; //寄件者信箱
$mail_us->FromName = '雷神排氣管-銷售中心'; //寄件者姓名
$mail_us->Subject = '【雷神排氣管】' . $userInfo['payway'] . '完成收款，客戶' . $userInfo['name'] . '的訂單：'; //郵件標題
$mail_us->Body = $Body_us;
$mail_us->IsHTML(true); //郵件內容為html ( true || false)
$mail_us->AddAddress('yongxin19861986@gmail.com'); //收件者郵件及名稱

$mail->Send();
$mail_us->Send();