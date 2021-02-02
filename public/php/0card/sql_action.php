<?php

header("Content-type: application/json; charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL);
require __DIR__ . '/config.php';

/**
 * SQL相關
 */
class SqlAction
{

    /**
     * notify紀錄
     */
    public static function LogNotify(String $data, String $header)
    {
        global $conn;
        $sql = "INSERT INTO
                `0card_notify` (`msg`,`header`)
                VALUES
                ('" . $data . "','" . $header . "')";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /**
     * 查詢訂單
     */
    public static function GetOrder($order_no)
    {
        global $conn;
        $sql = "SELECT * FROM `order` WHERE `order_no` = '" . $order_no . "'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    return $row;
                }
            } else {
                //訂單不存在
                return 'order not found';
            }
        }
    }

    /**
     * 儲存網站訂單
     */
    public static function CreateOrder($order_no, $user, $product, $pay)
    {
        global $conn;
        // mysqli_query($conn, "SET CHARACTER SET 'utf8mb4_unicode_ci'");
        $sql = "INSERT INTO `order` (`order_no`,`name`,`phone`,`email`,`address`,`commit`,`product_name`,`model`,`front_option`,`power_option`,`case_option`,`payway`,`staging`,`staging_price`,`total_price`,`is_paid`,`created_at`)
                VALUES(
                '$order_no',
                '" . $user['name'] . "',
                '" . $user['phone'] . "',
                '" . $user['email'] . "',
                '" . $user['address'] . "',
                '" . $user['commit'] . "',
                '" . $product['name'] . "',
                '" . $product['model'] . "',
                '" . $product['front_option'] . "',
                '" . $product['power_option'] . "',
                '" . $product['case_option'] . "',
                '零卡分期',
                '" . $pay['staging'] . "',
                '" . $pay['staging_price'] . "',
                '" . $pay['total_price'] . "',
                false,
                '" . date('Y-m-d H:i:s') . "')";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            self::ErrorLog("Error: " . $sql . "<br>" . $conn->error);
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /**
     * 更新網站訂單
     */
    public static function UpdateOrder($order_no)
    {
        global $conn;
        $sql = "UPDATE `order`
        SET
        `is_paid`='" . true . "'
        WHERE `order_no`='" . $order_no . "'";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            self::ErrorLog("Error: " . $sql . "<br>" . $conn->error);
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /**
     * 查詢0卡訂單
     */
    public static function GetZeroCardOrder($order_no)
    {
        global $conn;
        $sql = "SELECT * FROM `0card_order` WHERE `order_id` = '" . $order_no . "'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    return $row;
                }
            } else {
                //訂單不存在
                return 'order not found';
            }
        }
    }

    /**
     * 建立0卡訂單
     */
    public static function CreateZeroCardOrder($order_data)
    {
        global $conn;
        // mysqli_query($conn, "SET CHARACTER SET 'utf8mb4_unicode_ci'");
        $sql = "INSERT INTO `0card_order` (`order_id`,`transaction_state`,`amount`,`installment`,`fee_type`,`first_payment`,`each_payment`,`auth_day`,`customer_name`,`customer_phone`)
        VALUES(
            '" . $order_data->info_order->order_id . "',
            '" . $order_data->info_order->transaction_state . "',
            '" . $order_data->info_order->amount . "',
            '" . $order_data->info_order->installment . "',
            '" . $order_data->info_order->fee_type . "',
            '" . $order_data->info_order->first_payment . "',
            '" . $order_data->info_order->each_payment . "',
            '" . $order_data->info_order->auth_day . "',
            '" . $order_data->info_customer_json->cust_name . "',
            '" . $order_data->info_customer_json->cust_phone . "'
        )";

        if ($conn->query($sql) === true) {
            echo "success";
        } else {
            self::ErrorLog("Error: " . $sql . "<br>" . $conn->error);
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /**
     * 更新0卡訂單
     */
    public static function UpdateZeroCardOrder($order_data)
    {
        global $conn;
        $sql = "UPDATE `0card_order`
        SET
        `transaction_state`='" . $order_data->info_order->transaction_state . "',
        `amount`='" . $order_data->info_order->amount . "',
        `installment`='" . $order_data->info_order->installment . "',
        `fee_type`='" . $order_data->info_order->fee_type . "',
        `first_payment`='" . $order_data->info_order->first_payment . "',
        `each_payment`='" . $order_data->info_order->each_payment . "',
        `auth_day`='" . $order_data->info_order->auth_day . "',
        `customer_name`='" . $order_data->info_customer_json->cust_name . "',
        `customer_phone`='" . $order_data->info_customer_json->cust_phone . "'
        WHERE `order_id`='" . $order_data->info_order->order_id . "'";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            self::ErrorLog("Error: " . $sql . "<br>" . $conn->error);
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    /**
     * 錯誤log
     */
    public static function ErrorLog($error_msg)
    {
        global $conn;
        // mysqli_query($conn, "SET CHARACTER SET 'utf8mb4_unicode_ci'");
        $sql = "INSERT INTO `error_log` (`msg`) VALUES('$error_msg')";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            self::ErrorLog("Error: " . $sql . "<br>" . $conn->error);
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

}
