<template>
  <div class="home">
    <div class="section1">
      <div class="banner">
        <img src="@/assets/icon/雷神.jpg" alt="">
      </div>
    </div>

    <div class="section2">
      <div class="compare-title">
        Exhaust-產品比較
      </div>
      <div class="compare-container-title">
        <div class="compare-product-container">
          <div class="tx self">自己的品牌</div>
          <div class="img">
            <img src="../assets/icon/1.png" alt="" />
          </div>
        </div>
        <div class="compare-product-container">
          <div class="tx">其他品牌</div>
          <div class="img">
            <img src="../assets/icon/2.png" alt="" />
          </div>
        </div>
      </div>
      <div class="compare">
        <div
          class="compare-list"
          v-for="(item, item_index) in compare"
          :key="item_index"
        >
          <div class="title">
            <div class="small-title">{{ item.title }}</div>
            <a @click="item.open =! item.open">
              <img :class='{active:item.open}' src="https://img.icons8.com/plumpy/24/000000/plus.png" />
            </a>
          </div>

          <div class="hr">
            <hr />
          </div>
          <div class="compare_container_main" 
          v-show="item.open"
          >
            <div
              class="compare-container"
              v-for="(item_content, item_content_index) in item.content"
              :key="item_content_index"
            >
              <div class="compare-content">
                {{ item_content.name }}
              </div>
              <div class="tick">
                <div v-if="!item_content.product_1" class="space"></div>
                <div class="img">
                  <img
                    src="https://img.icons8.com/fluent/48/000000/double-tick.png"
                  />
                </div>
                <div v-if="item_content.product_1" class="space"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
    <!-- 閱讀性不佳 需修改 -->

    <div class="section3">
      <div class="section3-back"></div>
      <div class="section3-container">
        <div class="section3-title">
          Exhaust-產品圖
        </div>
        <div class="banner">
          <div class="backimg1"
          :class="{active: section3_banner1 ==true }">
            <img class="img1" v-bind:src="bg_1" alt="" >
            <img class="img1 mobile" v-bind:src="bg_1_mobile" alt="">
            <div class="content-title">
              <span><a @click="section3_banner1 =! section3_banner1">BK999</a></span>
            </div>
            <div class="container-content">
              <div class="content-back">
                <div class="content" v-show="section3_banner1">
                  <p>焊接</p>
                  <span>每一道焊接點都是牛王工廠師傅用心的結晶</span>
                  <p>材質介紹</p>
                  <span>不鏽鋼304材質因含鎳成分故抗氧化及耐蝕效果佳</span>
                  <p>彩繪鐵牌</p>
                  <span>隨管皆會附贈精緻的白鐵雷射雕刻牌</span>
                  <p>前段白鐵</p>
                  <span>抗蝕性較為優異，外觀上增添質感</span>
                </div>
              </div>
            </div>
            
            
          </div>
          <div class="backimg2"
          :class="{active: section3_banner2 == true}">
            <img class="img2" v-bind:src="bg_2" alt="">
            <img class="img2 mobile" v-bind:src="bg_2_mobile" alt="">
            <div class="content-title">
              <span><a @click="section3_banner2 =! section3_banner2">BX7</a></span>
            </div>
            <div class="container-content">
              <div class="content-back">
                <div class="content" v-show="section3_banner2">
                  <p>焊接</p>
                  <span>每一道焊接點都是牛王工廠師傅用心的結晶</span>
                  <p>材質介紹</p>
                  <span>不鏽鋼304材質因含鎳成分故抗氧化及耐蝕效果佳</span>
                  <p>彩繪鐵牌</p>
                  <span>隨管皆會附贈精緻的白鐵雷射雕刻牌</span>
                  <p>前段白鐵</p>
                  <span>抗蝕性較為優異，外觀上增添質感</span>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src

export default {
  name: "Home",
  components: {},
  data() {
    return {
      compare: [
        {
          title: "外觀造型",
          open:true,
          content: [
            {
              name: "防燙蓋選擇",
              product_1: true,
              product_2: false,
            },
            {
              name: "顏色選擇",
              product_1: false,
              product_2: true,
            },
          ],
        },
        {
          title: "配備",
          open:true,
          content: [
            {
              name: "側繞、下繞選擇",
              product_1: true,
              product_2: false,
            },
            {
              name: "到處繞選擇",
              product_1: false,
              product_2: true,
            },
          ],
        },
        {
          title: '材質',
          open: true,
          content: [
            {
              name: '碳纖維',
              product_1: true,
              product_2: true,
            },
            {
              name: '鈦合金',
              product_1: false,
              product_2: true,
            }
          ]
        },
        {
          title: "其他",
          open:true,
          content: [
            {
              name: "價錢",
              product_1: true,
              product_2: false,
            },
            {
              name: "政府驗證",
              product_1: true,
              product_2: false,
            },
          ],
        },
      ],
      bg_1: require("../assets/icon/model_big_BK999.png"),
      bg_1_mobile: require("../assets/icon/model_big_BK999_mobile.png"),
      bg_2: require("../assets/icon/model_big_BX7.png"),
      bg_2_mobile: require("../assets/icon/model_big_BX7_mobile.png"),
      section3_banner1: false,
      section3_banner2: false,
    };
  },
  computed: {
  },
};
</script>
