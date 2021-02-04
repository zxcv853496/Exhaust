export default {
    methods: {
        ArraySort(array, key, option) {
            if (option) {
                array.sort((a, b) => {
                    return a[key] - b[key]
                })
            } else {

                array.sort((a, b) => {
                    return b[key] - a[key]
                })
            }
            return array
        },
        SendPostData(data, type, url) {
            this.$store.commit('SetLoading', true)
            return new Promise(resolve => {
                this.axios.post(url, data)
                    .then((response) => {
                        this.$store.commit('SetLoading', false)
                        if (response.data.status != 'success') {
                            console.log("error")
                            console.log(response.data.msg)
                            resolve("error");
                        } else {
                            resolve(response.data);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            })
        },
    }
}