export default {
    methods: {
        validEmail(email) {
            var email_regx = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            if (email.length <= 0) {
                return "請填入電子郵件"
            } else if (!email_regx.test(email)) {
                return "請填入正確的電子郵件"
            } else {
                return true
            }
        },
        validPhone(phone) {
            if (phone.length <= 0) {
                return "請填入手機號碼"
            } else if (phone.length != 10 || phone.indexOf("09") != 0) {
                return "請填入正確的手機號碼"
            } else {
                return true
            }
        },
        validName(name) {
            var name_regx = /[^\u4e00-\u9fa5]/
            if (name.length <= 0) {
                return "請填入姓名"
            } else if (name_regx.test(name) || name.length > 5 || name.length < 2) {
                return "請填入五個字以內的中文姓名"
            } else {
                return true
            }
        },
        validAddress(address, city, area) {
            if (address.length < 4) {
                return "請填入正確的地址"
            } else if (city == '') {
                return "請選擇縣市"
            } else if (area == '') {
                return "請選擇地區"
            } else {
                return true
            }
        },
        validNotEmpty(data) {
            if (data.length <= 0) {
                return "此欄位不得留空"
            } else {
                return true
            }
        }
    }
};