<?php
header("Content-type: application/json; charset=utf-8");
require './config.php';
require './common_action.php';
require './sql_action.php';
$data = json_decode(file_get_contents('php://input'), true);

//UserInfo
$user = $data['user'];

//ProductInfo
$product = $data['product'];

//PayInfo
$pay                = $data['pay'];
$pay['staging']     = intval($pay['staging']);
$pay['payway_text'] = "";
$pay['payway_text'] = $pay['payway'] . "-" . $pay['pay_option'];

$order_no = 'THOR_' . time() . '00' . rand(1000, 9999);

//新增資料進order資料庫
$search_sql_data = SqlAction::createOrder($order_no, $user, $product, $pay);

##################SendMailToCustom##################
//載入PHPMAILER套件
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

//讀取模板
$pagecontents = file_get_contents("./blade/AfterPay.html");
//更改模板資訊
$Body = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
$Body = str_replace("OrderNo", $order_no, $Body);
$Body = str_replace("ProductName", $product['name'], $Body);
$Body = str_replace("OrderModel", $product['model'], $Body);
$Body = str_replace("FrontOption", $product['front_option'], $Body);
$Body = str_replace("PowerOption", $product['power_option'], $Body);
$Body = str_replace("CaseOption", $product['case_option'], $Body);
$Body = str_replace("PaymentType", "貨到付款-全額付款", $Body);
$Body = str_replace("PayTime", date('Y-m-d H:i:s'), $Body);
$Body = str_replace("OrderAmt", $pay['total_price'], $Body);
$Body = str_replace("UserName", $user['name'], $Body);
$Body = str_replace("UserAddress", $user['address'], $Body);
$Body = str_replace("UserEmail", $user['email'], $Body);
$Body = str_replace("UserPhone", $user['phone'], $Body);
$Body = str_replace("UserCommit", $user['commit'], $Body);
$Body = str_replace("MainMessage", "感謝您在雷神排氣管官方網站訂購的商品，我們已經收到了您貨到付款訂單", $Body);
$Body = str_replace("SubMessage", "等待我們進一步確認您的訂單資訊後將會立刻安排商品的製作", $Body);

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
$mail->FromName   = '雷神排氣管-販售中心'; //寄件者姓名
$mail->Subject    = '【雷神排氣管】已收到您的貨到付款訂單，近期將有專人與您聯絡：'; //郵件標題
$mail->Body       = $Body;
$mail->IsHTML(true); //郵件內容為html ( true || false)
$mail->AddAddress($user['email']); //收件者郵件及名稱

##################SendMailToUs##################

//讀取模板
$pagecontents = file_get_contents("./blade/AfterPay_us.html");
//更改模板資訊
$Body_us = str_replace("YearMonthDay", date("Y-m-d"), $pagecontents);
$Body_us = str_replace("TitleUserName", $user['name'], $Body_us);
$Body_us = str_replace("OrderNo", $order_no, $Body_us);
$Body_us = str_replace("ProductName", $product['name'], $Body_us);
$Body_us = str_replace("OrderModel", $product['model'], $Body_us);
$Body_us = str_replace("FrontOption", $product['front_option'], $Body_us);
$Body_us = str_replace("PowerOption", $product['power_option'], $Body_us);
$Body_us = str_replace("CaseOption", $product['case_option'], $Body_us);
$Body_us = str_replace("PaymentType", "貨到付款", $Body_us);
$Body_us = str_replace("PayTime", date('Y-m-d H:i:s'), $Body_us);
$Body_us = str_replace("OrderAmt", $pay['total_price'], $Body_us);
$Body_us = str_replace("UserName", $user['name'], $Body_us);
$Body_us = str_replace("UserAddress", $user['address'], $Body_us);
$Body_us = str_replace("UserEmail", $user['email'], $Body_us);
$Body_us = str_replace("UserPhone", $user['phone'], $Body_us);
$Body_us = str_replace("UserCommit", $user['commit'], $Body_us);
$Body_us = str_replace("MainMessage", "貨到付款訂單", $Body_us);
//https://i.imgur.com/Zc37oTb.gif

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
$mail_us->Subject    = '【雷神排氣管】貨到付款，客戶' . $user['name'] . '的訂單：'; //郵件標題
$mail_us->Body       = $Body_us;
$mail_us->IsHTML(true); //郵件內容為html ( true || false)
$mail_us->AddAddress('thorexhaust1999@gmail.com'); //收件者郵件及名稱

$mail->Send();
$mail_us->Send();

echo $order_no;
