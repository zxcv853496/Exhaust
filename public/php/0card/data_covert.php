<?php
$header = '{
    "Digest":"22b9923f7aa7176f28cfa3875299297e4d214a5870fb484d991a6ed95dcac131",
    "0Card-API-Key":"WNBP7ga22EggQ7RKP6bJT5izj76NCJBE"
}';
$data = '
{
    "result":"000",
    "result_message":"u6210u529f",
    "info_order":{
        "order_id":"BK20210120test02",
        "spanapp_id":"EPA99210120000008",
        "transaction_state":"003",
        "amount":1000,
        "installment":3,
        "fee_type":"vendor",
        "first_payment":334,
        "each_payment":333,
        "auth_day":"2021-01-20 11:57:12"
    },
    "info_customer_json":"sGaKwwCcsoS7ZzCg8wMp0CSy7Y5S62kh6kaFpax2/Z1DCYxCLPaPMlO2yYE97rrtpd4qdb1dLEgAvd3aRv/5marjev8jg5AjjydOJEXv3fM="
}
';
$data = json_decode($data);
$data->info_customer_json = AESdecode($data->info_customer_json);
print_r($data);

echo $data->info_order->order_id;

function AESdecode($encrypt)
{
    $app_cc_aes_iv = 'AMM5123456789012';
    $app_cc_aes_key = 'AMM51234567890123456789012345678';
    $data = openssl_decrypt($encrypt, "aes-256-cbc", $app_cc_aes_key, 0, $app_cc_aes_iv);
    return $data;
}
