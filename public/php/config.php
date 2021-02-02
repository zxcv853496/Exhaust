<?php
$DBNAME = "yongxind_thor";
$DBUSER = "yongxind_thor";
$DBPASSWD = ".K!FN5H%0{gS";
$DBHOST = "localhost";

//支付方式
$PayWayArray = [
    '信用卡一次付清' => 'Credit',
    '信用卡分期付款' => 'Credits',
    'WebATM' => 'WebATM',
    'ATM' => 'ATM',
    '超商代碼繳費' => 'CVS',
    '超商條碼繳費' => 'BARCODE',
    '貨到付款' => 'COD',
];
//藍新
$NewebpayConfig = [
    //正式版
    // 'HashKey' => 'DQ8PJ4pMGtOHLcBy5TqELUqBJsTWD5fA',
    // 'HashIV' => 'P7csS1IjPZPCXIVC',
    // 'MerchantID' => 'MS3334763824',
    //測試擁
    'HashKey' => 'ZbW2lV8xRGSxRqrgkAU9eCC0HJTzw5qI',
    'HashIV' => 'CDfM1PeFatBW16EP',
    'MerchantID' => 'MS19513031',
    'Version' => '1.5',
];

$conn = mysqli_connect($DBHOST, $DBUSER, $DBPASSWD, $DBNAME);
if (empty($conn)) {
    print mysqli_error($conn);
    die("無法連結資料庫" . mysqli_error($conn));
    exit;
}
if (!mysqli_select_db($conn, $DBNAME)) {
    die("無法選擇資料庫 NO");
}
// 設定連線編碼
mysqli_query($conn, "SET NAMES 'utf8'");