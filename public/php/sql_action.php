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
    public static function getScooterBrand()
    {
        global $conn;
        $sql = "SELECT * FROM `scooter_brand`";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $return_array = array();
                $count = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $return_array[$count] = (object) array();
                    $return_array[$count]->id = $row["id"];
                    $return_array[$count]->name = $row["name"];
                    $return_array[$count]->scooter = self::getScooters($row["id"]);
                    $count = $count + 1;
                }
                return $return_array;
            }
        } else {
            return "getScooterBrand error";
        }
    }

    public static function getScooters(String $brand)
    {
        global $conn;
        $sql = "SELECT * FROM `scooter` WHERE `brand` = '" . $brand . "'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $return_array = array();
                $count = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $return_array[$count] = (object) array();
                    $return_array[$count]->id = $row["id"];
                    $return_array[$count]->name = $row["name"];
                    $count = $count + 1;
                }
                return $return_array;
            }
        } else {
            return "getScooters error";
        }
    }

    public static function getSpecification()
    {
        global $conn;
        $sql = "SELECT * FROM `specification`";
        $result = mysqli_query($conn, $sql);
        $brand_array = array();

        if ($result->num_rows > 0) {
            // output data of each row
            $count = -1;
            while ($row = $result->fetch_assoc()) {

                $IfExist = -1;

                for ($i = 0; $i < count($brand_array); $i++) {
                    if ($brand_array[$i]->id == $row["product"]) {
                        $IfExist = $i;
                    }
                }

                if ($IfExist == -1) {
                    $count += 1;
                    $brand_array[$count] = (object) array();
                    $brand_array[$count]->id = $row["product"];
                    $brand_array[$count]->scooter = array();

                    $tmp_scooter = (object) array();
                    $tmp_scooter->id = $row["scooter"];
                    $tmp_scooter->middle_option = $row["middle_option"];
                    $tmp_scooter->bottom_option = $row["bottom_option"];

                    array_push($brand_array[$count]->scooter, $tmp_scooter);
                } else {
                    $tmp_scooter = (object) array();
                    $tmp_scooter->id = $row["scooter"];
                    $tmp_scooter->middle_option = $row["middle_option"];
                    $tmp_scooter->bottom_option = $row["bottom_option"];

                    array_push($brand_array[$IfExist]->scooter, $tmp_scooter);
                }
            }

            return $brand_array;
        } else {
            return "0 results";
        }
    }

    public static function getShop()
    {
        global $conn;
        $sql = "SELECT * from `bullking_test`";
        $result = mysqli_query($conn, $sql);
        $return_array = (object) array();

        if ($result->num_rows > 0) {
            // output data of each row
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                $return_array->data[$count] = (object) array();
                $return_array->data[$count]->id = $row["bullking_test_id"];
                $return_array->data[$count]->name = $row["name"];
                $return_array->data[$count]->address = $row["address"];
                $return_array->data[$count]->phone = $row["phone"];
                $return_array->data[$count]->coordinatesX = $row["coordinatesX"];
                $return_array->data[$count]->coordinatesY = $row["coordinatesY"];
                $count = $count + 1;
            }

            return $return_array;
        } else {
            return "0 results";
        }
    }

    public static function getorder(String $order_no)
    {
        global $conn;
        $sql = "SELECT * FROM `order` WHERE `order_no` = '$order_no'";
        $sql_result = $conn->query($sql);
        return mysqli_fetch_assoc($sql_result);
    }

    public static function createOrder($order_no, $user, $product, $pay)
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
                '" . $pay['payway_text'] . "',
                '" . $pay['staging'] . "',
                '" . $pay['staging_price'] . "',
                '" . $pay['total_price'] . "',
                false,
                '" . date('Y-m-d H:i:s') . "')";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public static function updateOrderPay($order_no)
    {
        global $conn;
        $sql = "UPDATE `order` SET `is_paid` = '" . true . "' WHERE `order_no` = '$order_no'";

        if ($conn->query($sql) === true) {
            return "success";
        } else {
            return "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public static function LogNotify(String $data, String $header)
    {
        global $conn;
        mysqli_query($conn, "SET CHARACTER SET utf8mb4");
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

}
