<?php
header("Content-Type: text/html; charset=utf-8");
require __DIR__ . '/config.php';
require __DIR__ . '/sql_action.php';
require __DIR__ . '/common_action.php';
require __DIR__ . '/mail_action.php';

// ini_set("display_errors", "On");
// error_reporting(E_ALL);

$transaction_state_array = [
    "001" => "消費者尚未在 payment_url 上進行操作",
    "002" => "此交易已轉專人審核處理中",
    "003" => "交易已核准但尚未請款",
    "004" => "交易請款中",
    "005" => "交易已撥款",
    "006" => "交易失敗(婉拒)",
    "007" => "交易在核准後通知取消或已全額退款",
    "008" => "訂單在審核時取消或逾時取消",
    "009" => "部份取消資料處理中",
];

$order_data = file_get_contents('php://input');
//$order_data = '{"result":"000","result_message":"u6210u529f","info_order":{"order_id":"BKEX_16111404889920066741000","spanapp_id":"EPA99210120000035","transaction_state":"003","amount":9416,"installment":12,"fee_type":"vendor","first_payment":781,"each_payment":785,"auth_day":"2021-01-20 19:02:09"},"info_customer_json":"sGaKwwCcsoS7ZzCg8wMp0CSy7Y5S62kh6kaFpax2/Z1DCYxCLPaPMlO2yYE97rrtpd4qdb1dLEgAvd3aRv/5marjev8jg5AjjydOJEXv3fM="}';
set_error_handler(function ($code, $msg, $file, $line, $context) {
    $error_log = 'Error[' . $code . ']: ' . $msg . ' - Place： ' . $file . ' (' . $line . ')';
    error_log($error_log . PHP_EOL, 3, __DIR__ . '/php-error.log');
    die;
});
$header = json_encode(apache_request_headers());
SqlAction::LogNotify(json_encode($order_data, true), $header);

$order_data = json_decode($order_data);
$order_data->info_order->transaction_state = $transaction_state_array[$order_data->info_order->transaction_state];
$order_data->info_customer_json = json_decode(CommonAction::createZeroCardDecrypt($order_data->info_customer_json));

//查詢訂單編號查看訂單是否已經存在
if (SqlAction::GetZeroCardOrder($order_data->info_order->order_id) != 'order not found') {
    //若存在則更新訂單
    SqlAction::LogNotify("update:" . $order_data->info_order->order_id, $order_data->info_order->transaction_state);
    SqlAction::UpdateZeroCardOrder($order_data);
    //若訂單狀態為003，更新為已付款，並寄出已付款信件
    if ($order_data->info_order->transaction_state == "交易已核准但尚未請款") {
        SqlAction::UpdateOrder($order_data->info_order->order_id);
        MailAction::OrderUpdate($order_data->info_order->order_id);
    }
    //若訂單狀態為006,7,8，更新為付款失敗，並寄出已付款失敗訂單已取消信件
    else if ($order_data->info_order->transaction_state == "交易失敗(婉拒)" || $order_data->info_order->transaction_state == "交易在核准後通知取消或已全額退款" || $order_data->info_order->transaction_state == "訂單在審核時取消或逾時取消") {
        MailAction::OrderUpdate($order_data->info_order->order_id);
    }
} else {
    //若不存在則新增訂單
    SqlAction::LogNotify("create", "");
    SqlAction::CreateZeroCardOrder($order_data);
    MailAction::OrderCreate($order_data->info_order->order_id);
    if ($order_data->info_order->transaction_state == "交易已核准但尚未請款") {
        SqlAction::UpdateOrder($order_data->info_order->order_id);
    }
}