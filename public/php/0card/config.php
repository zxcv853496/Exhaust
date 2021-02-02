<?php
$DBNAME = "yongxind_bullking";
$DBUSER = "yongxind_bullking";
$DBPASSWD = "UFx0jcdj.$^!";
$DBHOST = "localhost";

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
