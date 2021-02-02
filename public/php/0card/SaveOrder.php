<?php
header("Content-Type: text/html; charset=utf-8");

require __DIR__ . '/config.php';
require __DIR__ . '/sql_action.php';
require __DIR__ . '/common_action.php';

$data = json_decode(file_get_contents('php://input'), true);

//UserInfo
$user = $data['user'];

//ProductInfo
$product = $data['product'];

//PayInfo
$pay = $data['pay'];
$pay['staging'] = intval($pay['staging']);

//Order
$order_no = $data['order_no'];

//新增資料進order資料庫
$search_sql_data = SqlAction::createOrder($order_no, $user, $product, $pay);
echo CommonAction::returnMsg("success", "", "");