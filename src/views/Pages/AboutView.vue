<template>
  <NavBar></NavBar>
  <div class="contact">
    <div
      class="component viewproduct"
      @click="navigate('product')"
      v-if="isView"
    >
      <i class="fa-solid fa-angle-left"></i> Back to Contact Page
    </div>
    <div v-if="page === 'cart'" class="cart">
      <div class="cart_container">
        <div class="cart_info">
          <div class="info_side">
            <div class="all_items">
              <input type="checkbox" name="" id="" />
              <span>All Products</span>
            </div>
            <span class="price_quo">Price quotaiton</span>
            <span>Quantity</span>
            <span>Price</span>
            <span>Delete</span>
          </div>
        </div>
        <div class="cart_items">
          <div class="items_side" v-for="item in cart" :key="item.id">
            <div class="choose_item">
              <input type="checkbox" name="" id="" />
              <div
                class="item_image"
                :style="{ backgroundImage: `url(${item.image_link})` }"
              ></div>
              <div class="item_name">{{ item.name }}</div>
            </div>
            <span class="item_quo"> $ {{ item.price }}</span>
            <div class="item_quan">
              <button class="minus" @click="decreaseQuantity(item)">-</button>
              <span>{{ item.quantity }}</span>
              <button class="plus" @click="increaseQuantity(item)">+</button>
            </div>
            <span class="item_price">$ {{ item.price }}</span>
            <button @click="Delete(item)">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <div class="side-1" v-if="page === 'product'">
      <div class="side side_1">
        <p v-motion-slide-bottom></p>
        <h3 v-motion-slide-bottom>NEW</h3>
        <span>_</span>
        <h5>
          "Tìm nơi hoàn hảo để mua và trải nghiệm sách một cách hoàn hảo và
          chill nhất , chỉ có ở tại Bookapee"
        </h5>
      </div>
      <div v-motion-fade class="side side_2">
        <img src="@/assets/images/STAR.svg" alt="" v-motion-fade />
        <span v-motion-slide-bottom>ITEMS</span>
      </div>
    </div>
    <div class="side-2" v-if="page === 'product'">
      <div class="side-2__container">
        <div class="content-1">
          <p>CHÚNG TÔI ĐEM LẠI</p>
          <span>_</span>
        </div>
        <div class="content-2">
          <div class="content_items" v-motion-slide-bottom :delay="200">
            <img src="@/assets/images/STAR.svg" alt="" v-motion-fade />
            <div class="content_text">
              <div>ĐỘI NGŨ CÓ KINH NGHIỆM</div>
              <span>
                Mỗi người trong chúng tôi là bậc thầy trong lĩnh vực của mình –
                chúng tôi đã làm việc nhiều năm trong lĩnh vực của mình và đều
                có đam mê và tầm nhìn sáng tạo cho mọi dự án chúng tôi thực
                hiện.
              </span>
            </div>
          </div>
          <div class="content_items" v-motion-slide-bottom :delay="400">
            <img src="@/assets/images/STAR.svg" alt="" v-motion-fade />
            <div class="content_text">
              <div>CHẤT LƯỢNG TUYỆT VỜI</div>
              <span>
                Chúng tôi chú ý rất nhiều đến từng chi tiết nhỏ, vì chúng tôi
                biết rằng chúng tôi đang chịu trách nhiệm tạo ra nơi trú ẩn an
                toàn cho ai đó. Chúng tôi hợp tác với những nghệ nhân giỏi nhất
                để đảm bảo chất lượng nổi tiếng của mình.
              </span>
            </div>
          </div>
          <div class="content_items" v-motion-slide-bottom :delay="600">
            <img src="@/assets/images/STAR.svg" alt="" v-motion-fade />
            <div class="content_text">
              <div>NHỮNG NGƯỜI NHÌN XA TRÔNG RỘNG</div>
              <span>
                Chúng tôi không ngại vượt qua ranh giới. Là những người nhìn xa
                trông rộng của ngày mai, chúng tôi sẽ không từ chối thử nghiệm,
                vật liệu khác thường, hoặc những sự kết hợp không ngờ tới.
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="side-3" v-if="page === 'product'">
      <div class="side-3__container">
        <div class="side-3__text">
          <div class="side-3__text--1">
            <p>GẶP GỠ CHÚNG TÔI</p>
            <h5>MỘT TẬP THỂ, BIẾN GIẤC MƠ THÀNH HIỆN THỰC</h5>
            <span>
              Sức mạnh của chúng tôi nằm ở sự cá nhân hóa. Được thành lập bởi
              Trà Xuân, đội ngũ của Bookapee luôn nỗ lực để thu hút những tài
              năng tốt nhất trong nhiều lĩnh vực khác nhau, từ nhân viên đến xa
              hơn
            </span>
          </div>
          <span class="underline">_</span>
        </div>
        <div class="side-3__personnel">
          <div class="personnel">
            <img src="@/assets/images/hinh1.jpeg" alt="" />
            <p>TRA XUAN</p>
            <span>Founder • Interior designer</span>
          </div>
          <div class="personnel">
            <img src="@/assets/images/hinh2.jpeg" alt="" />
            <p>KHUONG TRAN</p>
            <span>CEO</span>
          </div>
          <div class="personnel">
            <img src="@/assets/images/hinh3.jpeg" alt="" />
            <p>XUAN KHUONG</p>
            <span>SEO</span>
          </div>
          <div class="personnel">
            <img src="@/assets/images/hinh4.jpeg" alt="" />
            <p>KHUONG TRA</p>
            <span>CFO</span>
          </div>
        </div>
      </div>
    </div>
    <div class="side-4" v-motion-fade v-if="page === 'product'">
      <div class="side-4__container">
        <div class="side-3__text">
          <div class="side-3__text--1">
            <p>LIÊN HỆ VỚI CHÚNG TÔI</p>
            <h5>MỘT TẬP THỂ, BIẾN GIẤC MƠ THÀNH HIỆN THỰC</h5>
            <span>
              Nếu bạn quan tâm đến việc tìm hiểu thêm về cách chúng tôi làm
              việc, có đề xuất kinh doanh, hoặc quan tâm đến việc mua hàng,
              chúng tôi rất mong được nghe từ bạn.
            </span>
          </div>
          <span class="underline">_</span>
        </div>
        <form action="" @submit.prevent="Submit">
          <div>Tên*</div>
          <input
            type="text"
            name=""
            id=""
            placeholder="Tên của bạn"
            v-model="name"
          />
          <div>Họ*</div>
          <input
            type="text"
            name=""
            id=""
            placeholder="Họ của bạn"
            v-model="last_name"
          />
          <div>Email của bạn*</div>
          <input
            type="text"
            name=""
            id=""
            placeholder="Email của bạn"
            v-model="email"
          />
          <div>Tin nhắn*</div>
          <textarea
            placeholder="Nhập tin nhắn của bạn"
            v-model="message"
          ></textarea>
          <button>GỬI</button>
        </form>
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>

<script>
import { ref } from "vue";
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";
export default {
  components: { NavBar, Route, Footer },
  setup() {
    const isView = ref(false);
    const page = ref("product");
    const navigate = (pages) => {
      page.value = pages;
      isView.value = !isView.value;
    };
    const isLogin = ref(true);
    const names = ref("");
    const cart = ref([]);
    const name = ref("");
    const last_name = ref("");
    const email = ref("");
    const message = ref("");

    const Submit = () => {
      name.value = "";
      last_name.value = "";
      email.value = "";
      message.value = "";
      alert("Your contact was sent...");
    };
    // onBeforeMount(() => {
    //   const user = firebase.auth().currentUser;
    //   const savedCart = localStorage.getItem("cart");
    //   if (savedCart) {
    //     cart.value = JSON.parse(savedCart);
    //   }
    //   if (user) {
    //     isLogin.value = false;
    //     names.value = user.email.split("@")[0];
    //   }
    // });
    const Delete = (item) => {
      const index = cart.value.findIndex((cartItem) => cartItem.id === item.id);
      if (index !== -1) {
        cart.value.splice(index, 1);
        // Lưu lại giỏ hàng sau khi xóa vào LocalStorage (nếu muốn)
        localStorage.setItem("cart", JSON.stringify(cart.value));
      }
    };
    const increaseQuantity = (item) => {
      item.quantity++;
      updatePrice(item);
    };

    const decreaseQuantity = (item) => {
      if (item.quantity > 1) {
        item.quantity--;
        updatePrice(item);
      }
    };

    const updatePrice = (item) => {
      item.price = item.unitPrice * item.quantity;
    };

    const handleLogin = () => {
      isLogin.value = false;
    };
    const Logout = () => {
      firebase
        .auth()
        .signOut()
        .then(() => console.log("Sign out"))
        .catch((err) => alert(err.message));
    };

    return {
      handleLogin,
      Logout,
      Delete,
      increaseQuantity,
      decreaseQuantity,
      updatePrice,
      isView,
      page,
      navigate,
      cart,
      isLogin,
      names,
      name,
      last_name,
      email,
      message,
      Submit,
      isView,
    };
  },
};
</script>

<style lang="scss" scoped>
@mixin center-item {
  display: flex;
  justify-content: center;
  align-items: center;
}
$text-color: rgb(144, 143, 143);
$items-color: #474747;
$content-color: #e8554e;
$content-color2: #004b80;
$font: "Lato";
///////////products

body {
  padding: 0;
  margin: 0;

  font-family: "Lato", sans-serif;
  letter-spacing: 0.5px;
}

nav {
  margin-bottom: 0px;
}
.contact {
  width: 100%;
  height: auto;
  margin-bottom: 100px;
}
.side-1 {
  color: #474747;
  width: 1100px;
  height: 500px;
  background-color: #004b80;
  margin: 0 auto;
  position: relative;
  top: 80px;
  display: flex;
  justify-content: space-between;
  align-content: center;
  .side {
    width: 50%;
    height: 100%;
    background-color: #f3f3f3;
  }
  .side_1 {
    p {
      font-size: 35px;
      font-weight: bold;
      margin: 0;
      padding: 0;
      position: relative;
      top: 120px;
      left: 100px;
    }
    h3 {
      font-size: 150px;
      font-weight: bold;
      margin: 0;
      padding: 0;
      position: relative;
      top: 120px;
      left: 100px;
    }
    span {
      font-size: 190px;
      font-weight: bold;
      position: relative;
      top: -20px;
      left: 100px;
    }
    h5 {
      font-weight: lighter;
      font-size: 11px;
      position: relative;
      top: -20px;
      left: 100px;
      width: 400px;
    }
  }
  .side_2 {
    background-size: cover;
    background-position: right 20%;
    background-image: url(@/assets/images/b3.jpg);
    img {
      position: absolute;
      top: -50px;
      right: -90px;
    }
    span {
      font-size: 150px;
      font-weight: bold;
      position: relative;
      top: 160px;
      left: -50px;
    }
  }
}
.side-2 {
  width: 100%;
  height: 600px;
  background-color: #f28b82;
  position: relative;
  top: 100px;
  color: #262525;
  @include center-item();
  .side-2__container {
    width: 60%;
    height: 70%;

    display: flex;
    justify-content: space-between;
    align-items: center;
    .content-1 {
      width: 40%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      p {
        font-size: 50px;
        font-weight: bold;
        letter-spacing: 1px;
        position: relative;
        top: -40px;
      }
      span {
        font-size: 150px;
        font-weight: bold;
      }
    }
    .content-2 {
      width: 60%;
      height: 100%;

      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      .content_items {
        width: 100%;
        height: 30%;

        display: flex;
        justify-content: space-between;
        align-items: center;

        .content_text {
          width: 80%;
          height: 100%;
          text-align: justify;
          position: relative;
          top: 20.25px;
          left: -10px;
          div {
            font-weight: bold;
            margin-bottom: 5px;
          }
          span {
            font-size: 10px;
          }
        }
      }
    }
  }
}
.viewproduct {
  top: 30px;
}
.side-3 {
  width: 100%;
  height: 800px;

  position: relative;
  top: 100px;
  @include center-item();
  .side-3__container {
    width: 60%;
    height: 70%;
    display: flex;
    .side-3__text {
      width: 37%;
      height: 100%;

      .side-3__text--1 {
        width: 95%;
        height: 35%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: $items-color;
        p {
          font-size: 40px;
          font-weight: bold;
          margin: 0;
          padding: 0;
          letter-spacing: 5px;
        }
        h5 {
          font-size: 12px;
          margin: 0;
          padding: 0;
          font-weight: lighter;
        }
        span {
          font-size: 10px;
          width: 80%;
        }
      }
      .underline {
        font-size: 150px;
        position: relative;
        top: 220px;
      }
    }
    .side-3__personnel {
      width: 65%;
      height: 100%;

      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-template-rows: repeat(2, 1fr);
      gap: 10px;

      .personnel {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: $items-color;

        img {
          width: 66%;
          height: 80%;
        }
        p {
          margin: 0;
          padding: 0;
          width: 100%;
          height: 5%;
          font-weight: bold;
        }
        span {
          width: 100%;
          height: 8%;
          font-size: 10px;
        }
      }
    }
  }
}
.side-4 {
  width: 100%;
  height: 600px;
  position: relative;
  top: 100px;
  background-color: #f28b82;
  color: $items-color;
  @include center-item();
  .side-4__container {
    width: 60%;
    height: 80%;

    display: flex;
    .side-3__text {
      width: 37%;
      height: 100%;

      .side-3__text--1 {
        width: 95%;
        height: 35%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: $items-color;
        p {
          font-size: 40px;
          font-weight: bold;
          margin: 0;
          padding: 0;
          letter-spacing: 5px;
        }
        h5 {
          font-size: 12px;
          margin: 0;
          padding: 0;
          font-weight: lighter;
        }
        span {
          font-size: 10px;
          width: 80%;
        }
      }
      .underline {
        font-size: 150px;
        position: relative;
        top: 140px;
      }
    }
    form {
      width: 63%;
      height: 100%;

      display: flex;
      flex-direction: column;
      justify-content: space-between;
      font-size: 12px;
      div {
        position: relative;
        top: 10px;
        left: 50px;
        font-family: $font;
      }
      input {
        width: 80%;
        height: 8%;
        position: relative;
        font-family: $font;
        left: 50px;
        text-indent: 10px;
        &::placeholder {
          font-size: 10px;
          text-indent: 10px;
        }
        border: none;
      }
      textarea {
        width: 80%;
        height: 30%;
        position: relative;
        font-family: $font;
        left: 50px;
        color: #1f1e1e;
        border: none;
        text-indent: 10px;
        &::placeholder {
          position: relative;
          top: 5px;
          font-size: 10px;
          text-indent: 10px;
        }
      }
      button {
        width: 170px;
        height: 35px;
        background-color: #262525;
        color: #f3f3f3;
        border: none;
        position: relative;
        right: -330px;
        cursor: pointer;
        font-weight: bold;
        font-family: $font;
      }
    }
  }
}
.information,
.copyright {
  position: relative;
  top: 100px;
}
</style>
