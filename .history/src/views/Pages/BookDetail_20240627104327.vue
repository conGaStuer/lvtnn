<template>
  <NavBar></NavBar>
  <Route></Route>
  <div class="container" v-if="book">
    <div class="book-detail">
      <div
        class="book-image"
        :style="{ backgroundImage: `url(${book.HinhAnh})` }"
      ></div>
      <div class="book-info">
        <h1>{{ book.TenSach }}</h1>
        <p class="author">
          Tác giả :
          <router-link
            :to="{
              name: 'AuthorBook',
              params: { id: book.MaTacGia, name: book.TacGia },
            }"
            >{{ book.TacGia }}</router-link
          >
        </p>

        <p class="publisher">
          Nhà Xuất Bản :
          <router-link
            :to="{
              name: 'Publisher',
              params: { id: book.MaNhaXuatBan, name: book.NhaXuatBan },
            }"
            >{{ book.NhaXuatBan }}</router-link
          >
        </p>

        <p class="price">
          <span>{{ book.DonGia }} đồng</span> {{ discountedPrice }} đồng
        </p>
        <p class="description">{{ book.ChiTiet }}</p>
        <div class="actions">
          <input type="number" class="quantity" v-model="quantity" />
          <button @click="addToCart">THÊM VÀO GIỎ</button>
        </div>
        <div class="metas">
          <p class="meta">
            <i class="pi pi-truck"></i>Giao hàng từ 2 đến 3 ngày
          </p>
          <p class="meta">
            <i class="pi pi-wallet"></i>An toàn và bảo mật tuyệt đối
          </p>
        </div>

        <p class="categories">
          Danh mục:
          <span>
            <router-link
              v-for="(category, index) in book.DanhMuc.split(',')"
              :key="index"
              :to="{
                name: 'Category',
                params: {
                  id: book.MaDanhMuc.split(',')[index],
                  name: category,
                },
              }"
              >{{ category }}</router-link
            >
          </span>
        </p>
        <p class="publisher lang">
          Ngôn ngữ :
          <router-link
            :to="{
              name: 'Languages',
              params: { id: book.MaNgonNgu, name: book.NgonNgu },
            }"
            >{{ book.NgonNgu }}</router-link
          >
        </p>
        <p class="publisher lang stock">
          Số lượng còn trong kho : {{ book.SoLuong }}
        </p>
      </div>
    </div>
  </div>
  <div class="tabb" v-if="book">
    <nav>
      <button
        @click="selectedTab = 'description'"
        :class="{ active: selectedTab === 'description' }"
      >
        BÌNH LUẬN & ĐÁNH GIÁ
      </button>

      <button
        @click="selectedTab = 'bookDetails'"
        :class="{ active: selectedTab === 'bookDetails' }"
      >
        CHI TIẾT SÁCH
      </button>
    </nav>
    <div v-if="selectedTab === 'description'">
      <div class="comment" v-for="comment in comments" :key="comment.maDG">
        <div
          class="avatar"
          :style="{
            backgroundImage: `url('http://localhost/LVTN/book-store/src/api/${comment.hinh}')`,
          }"
        ></div>
        <div class="comment-info">
          <div class="comment-user">
            <span class="taikhoan">{{ comment.taikhoan }}</span> -
            <span class="time">{{ comment.ngayDG }}</span>
          </div>
          <div class="comment-detail">
            <p>{{ comment.noidung }}</p>
            <Rating
              v-model="comment.rating"
              :cancel="false"
              class="star"
              disabled
            />
            <span v-if="isEmployee" @click="repComment(comment.maDG)"
              >Trả lời</span
            >
            <span v-if="isEmployee" @click="deleteComment(comment.maDG)"
              >Xóa</span
            >
            <form
              @submit.prevent="handleComment(comment.maDG)"
              v-if="isRep === comment.maDG"
            >
              <input type="text" v-model="userComment" />
              <Rating v-model="value" :cancel="false" class="star star1" />

              <button>Gửi</button>
            </form>
          </div>
        </div>
      </div>

      <form @submit.prevent="handleComment">
        <input type="text" v-model="userComment" />
        <Rating v-model="value" :cancel="false" class="star star1" />

        <button>Gửi</button>
      </form>
    </div>
    <div v-else-if="selectedTab === 'bookDetails'">
      <p>Thông tin sách</p>
      <div class="book-infoo">
        <div class="info-row">
          <p class="info-label">Nhà Xuất Bản</p>
          <p class="info-value">{{ book.NhaXuatBan }}</p>
        </div>
        <div class="info-row">
          <p class="info-label">Ngôn Ngữ</p>
          <p class="info-value">{{ book.NgonNgu }}</p>
        </div>
        <div class="info-row">
          <p class="info-label">Ngày ra mắt</p>
          <p class="info-value">Apr 1889</p>
        </div>
        <div class="info-row">
          <p class="info-label">Tái bản</p>
          <p class="info-value">Lần 1</p>
        </div>

        <div class="info-row">
          <p class="info-label">Loại</p>
          <p class="info-value">Giấy</p>
        </div>
        <div class="info-row">
          <p class="info-label">Loại</p>
          <p class="info-value">Sách</p>
        </div>
      </div>
    </div>
  </div>
  <section class="featured-books" v-if="relatedBooks.length">
    <div class="containerr">
      <div class="header">
        <h2>Sách liên quan</h2>
      </div>
      <div class="book-list">
        <router-link
          class="bookk"
          v-for="relatedBook in relatedBooks"
          :key="relatedBook.MaSach"
          @mouseover="hover = relatedBook.MaSach"
          @mouseleave="hover = null"
          :to="{ name: 'bookDetail', params: { id: relatedBook.MaSach } }"
        >
          <div class="image-container">
            <img :src="relatedBook.HinhAnh" :alt="relatedBook.TenSach" />
            <transition name="fade">
              <div v-if="hover === relatedBook.MaSach" class="overlay">
                <div class="icon"><i class="pi pi-eye"></i></div>
                <div class="icon"><i class="pi pi-heart"></i></div>
                <div class="icon"><i class="pi pi-shopping-cart"></i></div>
              </div>
            </transition>
          </div>
          <div class="book-detailss">
            <span class="categoryy">{{ relatedBook.DanhMuc }}</span>
            <h3>{{ relatedBook.TenSach }}</h3>
            <p class="authorr">Tác giả: {{ relatedBook.TacGia }}</p>
            <p class="pricee">
              <span>{{ relatedBook.DonGia }} đồng </span>
              {{
                relatedBook.DonGia -
                (relatedBook.DonGia * relatedBook.KhuyenMai) / 100
              }}
              đồng
            </p>
          </div>
        </router-link>
      </div>
    </div>
  </section>

  <Footer></Footer>
</template>

<script>
import { onMounted, ref, watch, computed } from "vue";
import axios from "axios";
import { onBeforeRouteUpdate, useRoute } from "vue-router";
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";
import Rating from "primevue/rating";
import { message } from "ant-design-vue";

export default {
  components: {
    NavBar,
    Route,
    Footer,
    Rating,
  },
  setup() {
    const quantity = ref(1);
    const value = ref(null);
    const book = ref(null);
    const route = useRoute();
    const hover = null;

    const getDetailBook = (bookId) => {
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getDetailBook.php?id=${bookId}`
        )
        .then((res) => {
          if (res.data) {
            book.value = res.data;
            getRelatedBooks(book.value.DanhMuc, book.value.MaSach);
            discountedPrice.value = calculateDiscountedPrice(
              book.value.DonGia,
              book.value.KhuyenMai
            );
          } else {
            console.log("No data available");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };

    const relatedBooks = ref([]);

    const getRelatedBooks = (category, currentBookId) => {
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getRelatedBooks.php?category=${category}&id=${currentBookId}`
        )
        .then((res) => {
          if (res.data) {
            relatedBooks.value = res.data;
          } else {
            console.log("No related data available");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };

    onBeforeRouteUpdate(() => {
      window.scrollTo(0, 0);
    });

    onMounted(() => {
      getDetailBook(route.params.id);
      getAllComment();
      console.log(userData.value);
    });

    watch(route, (newRoute) => {
      getDetailBook(newRoute.params.id);
    });

    const selectedTab = ref("description");

    const addToCart = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/checkStock.php", {
          maSach: book.value.MaSach,
          soLuong: quantity.value,
        })
        .then((res) => {
          if (res.data.status === "success") {
            axios
              .post("http://localhost/LVTN/book-store/src/api/addtocart.php", {
                userId: currentUser.maND,
                maSach: book.value.MaSach,
                donGia: discountedPrice.value,
                soLuong: quantity.value,
              })
              .then((res) => {
                console.log("Book added to cart: ", res.data);
                message.success("Book added to cart");
                window.location.reload();
              })
              .catch((err) => {
                console.log("Error: ", err);
                message.error("This product is temporarily out of stock");
              });
          } else {
            message.error("The quantity of books is not enough in stock");
          }
        })
        .catch((err) => {
          console.log("Stock checking error: ", err);
        });
    };

    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    const userData = ref({ ...currentUser });

    const userComment = ref("");

    const handleComment = (rep = null) => {
      const content = userComment.value; // Access as userComment.value
      const ratingValue = value.value;
      const bookId = route.params.id;

      axios
        .post(`http://localhost/LVTN/book-store/src/api/postComment.php`, {
          MaSach: bookId,
          MaKH: currentUser.maND,
          noidung: content,
          rating: ratingValue,
          rep: rep,
        })
        .then((response) => {
          message.success("Bình luận của bạn đã được gửi");
          getAllComment();
        })
        .catch((error) => {
          console.error(error);
        });
    };

    const comments = ref();
    const getAllComment = () => {
      const bookId = route.params.id;
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getAllComment.php?bookId=${bookId}`
        )
        .then((res) => {
          if (res.data) {
            comments.value = res.data;
          } else {
            console.log("Error");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };

    const visibleComments = ref(5);
    const loadMoreComments = () => {
      visibleComments.value += 5;
    };

    const isRep = ref(null);
    const repComment = (commentId) => {
      if (currentUser.maVaiTro === "2" || currentUser.maVaiTro === "1") {
        isRep.value = commentId;
      }
    };

    const isEmployee = computed(() => {
      return currentUser.maVaiTro === "2";
    });

    const isEmployeeOrCustomer = computed(() => {
      return currentUser.maVaiTro === "2" || currentUser.maVaiTro === "1";
    });

    const discountedPrice = ref(0);
    const calculateDiscountedPrice = (originalPrice, discount) => {
      return originalPrice - (originalPrice * discount) / 100;
    };

    return {
      book,
      addToCart,
      selectedTab,
      hover,
      relatedBooks,
      userData,
      currentUser,
      value,
      handleComment,
      getAllComment,
      comments,
      visibleComments,
      loadMoreComments,
      isRep,
      repComment,
      discountedPrice,
      quantity,
      isEmployee,
      isEmployeeOrCustomer,
      userComment,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/bookdetail.scss";

.featured-books {
  text-align: center;
  padding: 40px 20px;
  font-family: "Noto Sans";
  margin-bottom: 120px;
  .containerr {
    max-width: 1200px;
    margin: 0 auto;

    .header {
      margin-bottom: 20px;

      .badge {
        display: inline-block;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
        font-weight: bold;
        margin-bottom: 10px;
      }

      h2 {
        font-size: 36px;
        margin-bottom: 10px;
      }

      p {
        color: #666;
        margin-bottom: 30px;
      }
    }

    .book-list {
      display: flex;
      justify-content: flex-start;
      flex-wrap: wrap;

      .bookk {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px;
        flex-basis: calc(25% - 20px);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        cursor: pointer;
        height: 500px;
        text-decoration: none;
        .image-container {
          position: relative;
          width: 100%;
          height: 65%;
          background-color: #f8f9fa;
          display: flex;
          align-items: center;
          justify-content: center;
          border-radius: 8px;
          border-bottom-left-radius: 0px;
        }

        img {
          width: 70%;
          height: 80%;
          border-radius: 8px;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .overlay {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          background: rgba(#dd907b, 0.7);
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 10px;
          height: 100%;
        }

        .overlay .icon {
          font-size: 18px;
          color: black;
          cursor: pointer;
          transition: all 0.3s ease-in-out;
          width: 50px;
          height: 50px;
          background-color: #f0f0f0;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .overlay .icon:hover {
          background-color: black;
          color: #fff;
        }

        .book-detailss {
          padding: 15px;
          text-align: left;
          width: 100%;
          height: 35%;
          .categoryy {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
          }

          h3 {
            font-size: 18px;
            margin: 5px 0;
            color: #373636;
          }

          .authorr {
            color: #999;
            font-size: 14px;
            margin-bottom: 10px;
          }

          .pricee {
            font-size: 16px;
            font-weight: bold;
            color: #e55a5a;

            span {
              color: #999;
              text-decoration: line-through;
              margin-right: 20px;
            }
          }
        }
      }
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
