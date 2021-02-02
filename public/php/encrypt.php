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
global $PayWayArray;
$pay = $data['pay'];
$pay['staging'] = intval($pay['staging']);
$pay['payway_text'] = $pay['payway'] . "-" . $pay['pay_option'];
$pay['payway'] = $PayWayArray[$pay['payway']];

//Order
$order_no = 'THOR_' . time() . '00' . rand(1000, 9999);

//新增資料進order資料庫
$search_sql_data = SqlAction::createOrder($order_no, $user, $product, $pay);

global $NewebpayConfig;
$PayWayActive = [
    'Credit' => 0,
    'Credits' => 0,
    'WebATM' => 0,
    'ATM' => 0,
    'CVS' => 0,
    'BARCODE' => 0,
];
$PayWayActive[$pay['payway']] = 1;
//資料陣列
$trade_info_arr = [
    'MerchantID' => $NewebpayConfig['MerchantID'],
    'RespondType' => 'JSON',
    'TimeStamp' => time(),
    'Version' => '1.5',
    'MerchantOrderNo' => $order_no,
    'Amt' => $pay['total_price'],
    'ItemDesc' => $product['name'],
    'Email' => $user['email'],
    'EmailModify' => 0,
    'LoginType' => 0,
    'CREDIT' => $PayWayActive['Credit'],
    'InstFlag' => $pay['staging'],
    'WEBATM' => $PayWayActive['WebATM'],
    'VACC' => $PayWayActive['ATM'],
    'CVS' => $PayWayActive['CVS'],
    'BARCODE' => $PayWayActive['BARCODE'],
    'ClientBackURL' => 'https://thor-exhaust.tw/',
    'ReturnURL' => 'https://thor-exhaust.tw/php/redirect.php',
    'NotifyURL' => 'https://thor-exhaust.tw/php/notify.php',
    'CustomerURL' => 'https://thor-exhaust.tw/php/redirect.php',
];

$TradeInfo = CommonAction::createNewebpayEncrypt($trade_info_arr);
$TradeSha = strtoupper(hash('sha256', 'HashKey=' . $NewebpayConfig['HashKey'] . '&' . $TradeInfo . '&' . 'HashIV=' . $NewebpayConfig['HashIV']));
$response = [
    'status' => 'success',
    'MerchantID' => $NewebpayConfig['MerchantID'],
    'TradeInfo' => $TradeInfo,
    'TradeSha' => $TradeSha,
    'Version' => $NewebpayConfig['Version'],
];
//回傳參數
echo CommonAction::returnMsg("success", json_encode($response), "");