import qs from 'qs';

export default {
  methods: {
    async CheckOrderRecord() {
      //查詢該筆訂單是否已經紀錄過
      //若無則觸發GA事件並UpdateOrderRecord
      //若有則不做任何事
      let paid_result = await this.SendPostData(
        qs.stringify({
          id: this.$route.query.order_no,
        }),
        '',
        process.env.VUE_APP_BASE_API + 'GetOrderRecord.php'
      );
      if (paid_result.status == 'success') {
        let order_data = JSON.parse(paid_result.msg);
        if (order_data.is_record == 0) {
          const vm = this;
          vm.$gtm.trackEvent({
            event: 'order_finish',
            category: '訂單事件', // 類別 字元(必填)
            action: 'paid', // 動作 字元(必填)
            label: '完成訂單', // 標籤 字元(選填)
            value: order_data.total_price, // 標籤 數字(選填)
            product_price: order_data.total_price,
            product_name: order_data.product_name,
            user_name: order_data.name,
            case_option: order_data.case_option,
            power_option: order_data.power_option,
            pay_way: order_data.payway.split('-')[0],
            pay_option: order_data.payway.split('-')[1],
          });
          await this.UpdateOrderRecord();
        }
      }
    },
    async UpdateOrderRecord() {
      //更新Order is_record
      await this.SendPostData(
        qs.stringify({
          id: this.$route.query.order_no,
        }),
        '',
        process.env.VUE_APP_BASE_API + 'UpdateOrderRecord.php'
      );
    },
  },
};
