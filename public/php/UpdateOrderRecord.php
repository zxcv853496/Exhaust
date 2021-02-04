<?php
header("Content-Type: text/html; charset=utf-8");

require __DIR__ . '/config.php';
require __DIR__ . '/sql_action.php';
require __DIR__ . '/common_action.php';
ini_set("display_errors", "On");
error_reporting(E_ALL);

$order_no = $_POST['id'];

$scooter_sql_data = SqlAction::updateorderRecord($order_no);
echo CommonAction::returnMsg("success", json_encode($scooter_sql_data), "");
