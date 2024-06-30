<template>
  <NavBar></NavBar>
  <Route></Route>
  <div class="wrapp">
    <section class="book-grid">
      <div class="container">
        <div class="header">
          <div class="view-options">
            <button
              @click="setView('grid')"
              :class="{ active: view === 'grid' }"
            >
              <i class="pi pi-th-large"></i>
            </button>
            <button
              @click="setView('list')"
              :class="{ active: view === 'list' }"
            >
              <i class="pi pi-list"></i>
            </button>
          </div>
          <div class="filter">
            <div><i class="pi pi-bars"></i><span>Filter</span></div>
            <select v-model="sortBy">
              <option value="default">Sắp xếp mặc định</option>
              <option value="priceAsc">Giá : Từ thấp đến cao</option>
              <option value="priceDesc">Giá : Từ cao đến thấp</option>
            </select>
          </div>
        </div>
        <div :class="[view === 'grid' ? 'grid-view' : 'list-view']">
          <router-link
            class="book"
            v-for="book in sortedBooks"
            :key="book.MaSach"
            @mouseover="hover = book.MaSach"
            @mouseleave="hover = null"
            :to="{ name: 'bookDetail', params: { id: book.MaSach } }"
          >
            <div class="image-container">
              <img :src="book.HinhAnh" :alt="book.TenSach" />
              <transition name="fade">
                <div v-if="hover === book.MaSach" class="overlay">
                  <div class="icon"><i class="pi pi-eye"></i></div>
                  <div class="icon"><i class="pi pi-heart"></i></div>
                  <router-link to="/cart"
                    ><div class="icon"><i class="pi pi-shopping-cart"></i></div
                  ></router-link>
                </div>
              </transition>
            </div>
            <div class="book-details">
              <span class="category">{{ book.DanhMuc }}</span>
              <h3>{{ book.TenSach }}</h3>
              <p class="author">Tác giả: {{ book.TacGia }}</p>
              <p class="price">
                <span>{{ book.DonGia }} đồng </span>
                {{ book.DonGia - (book.DonGia * book.KhuyenMai) / 100 }} đồng
              </p>
              <p class="detail">{{ book.ChiTiet }}</p>
              <router-link to="/cart">
                <button><i class="pi pi-shopping-cart"></i></button>
              </router-link>
            </div>
          </router-link>
        </div>
      </div>
    </section>
    <section class="list-cate">
      <p>DANH MỤC</p>
      <ul>
        <li v-for="category in categories" :key="category.MaDanhMuc">
          <router-link
            :to="{
              name: 'Category',
              params: { id: category.MaDanhMuc, name: category.DanhMuc },
            }"
          >
            {{ category.DanhMuc }}
          </router-link>
        </li>
      </ul>
    </section>
  </div>
  <Footer></Footer>
</template>

<script>
import { onMounted, ref, watch, computed } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";

export default {
  components: {
    NavBar,
    Route,
    Footer,
  },
  setup() {
    const books = ref([]);
    const route = useRoute();
    const categories = ref([]);
    const view = ref("grid");
    const sortBy = ref("default");
    const hover = ref(null);

    const setView = (type) => {
      view.value = type;
    };

    const fetchCategories = async () => {
      try {
        const response = await axios.get(
          "http://localhost/LVTN/book-store/src/api/getCategories.php"
        );
        if (response.data) {
          categories.value = response.data;
        }
      } catch (err) {
        console.error("Error fetching categories", err);
      }
    };

    const fetchBooksByCategory = async (categoryName) => {
      try {
        const response = await axios.get(
          `http://localhost/LVTN/book-store/src/api/getCategory.php?categoryName=${categoryName}`
        );
        if (response.data) {
          books.value = response.data;
        }
      } catch (err) {
        console.error("Error fetching books by category", err);
      }
    };

    const updateBooks = () => {
      const categoryName = route.params.name;
      console.log("Category ID: ", categoryName); // Debugging statement
      if (categoryName) {
        fetchBooksByCategory(categoryName);
      }
    };

    watch(
      () => route.params.name,
      (newId, oldId) => {
        console.log("Route ID changed:", newId); // Debugging statement
        if (newId !== oldId) {
          updateBooks();
        }
      },
      { immediate: true }
    );

    onMounted(() => {
      fetchCategories();
      updateBooks();
    });

    const sortedBooks = computed(() => {
      let sorted = [...books.value];
      if (sortBy.value === "priceAsc") {
        sorted.sort((a, b) => a.DonGia - b.DonGia);
      } else if (sortBy.value === "priceDesc") {
        sorted.sort((a, b) => b.DonGia - a.DonGia);
      }
      return sorted;
    });

    return {
      books,
      view,
      sortBy,
      setView,
      hover,
      categories,
      sortedBooks,
    };
  },
};
</script>

<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto Sans:wght@400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap");
.wrapp {
  width: 85%;
  height: auto;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  .list-cate {
    width: 300px;
    height: 800px;
    position: absolute;
    top: 370px;
    left: 1100px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    p {
      font-weight: bold;
      font-size: 20px;
    }
    a {
      text-decoration: none;
      color: #333;
    }
    li {
      list-style: none;
      margin-bottom: 20px;
      font-style: italic;
      position: relative;
      left: -40px;
      font-size: 17px;
    }
  }
  .book-grid {
    font-family: "Noto Sans";
    padding: 20px;
    a {
      text-decoration: none;
      color: #333;
    }
    .container {
      max-width: 900px;
      padding-top: 100px;
      .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 60px;
        width: 98.5%;

        .view-options {
          button {
            margin-right: 10px;
            border: none;
            width: 35px;
            height: 35px;
            cursor: pointer;
            background-color: white;
            border: 1px solid rgb(187, 180, 180);
            font-weight: bold;
            color: rgb(187, 180, 180);
            &.active {
              border: 1px solid #333;
              color: #333;
            }
          }
        }

        .filter {
          display: flex;
          align-items: center;
          div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 0.5px solid rgb(187, 180, 180);
            padding: 9px 25px;
            cursor: pointer;
            margin-right: 20px;
            color: rgb(187, 180, 180);

            i {
              margin-right: 10px;
            }
          }

          select {
            width: 215px;
            font-family: "Noto Sans";
            color: rgb(187, 180, 180);

            padding: 10px;
            border: 0.5px solid rgb(187, 180, 180);
            cursor: pointer;
          }
        }
      }

      .grid-view {
        display: flex;
        flex-wrap: wrap;

        width: 100%;
        .book {
          background: #fff;
          border-radius: 10px;
          overflow: hidden;
          margin: 20px 10px;
          flex-basis: calc(30% - 20px);
          display: flex;
          flex-direction: column;
          align-items: center;
          position: relative;
          cursor: pointer;
          height: 570px;
          width: 400px;
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

          .book-details {
            padding: 15px;
            text-align: left;
            width: 100%;
            height: 35%;
            .category {
              color: #666;
              font-size: 14px;
              margin-bottom: 5px;
            }

            h3 {
              font-size: 18px;
              margin: 5px 0;
            }

            .author {
              color: #999;
              font-size: 14px;
              margin-bottom: 10px;
            }

            .price {
              font-size: 16px;
              font-weight: bold;
              color: #f28b82;
              position: relative;
              top: -10px;
              display: flex;
              justify-content: space-between;
              span {
                color: #999;
                text-decoration: line-through;
                margin-right: 10px;
              }
            }
            .detail {
              display: none;
            }
            button {
              display: none;
            }
          }
        }
      }

      .list-view {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        .book {
          display: flex;
          background: #fff;
          border-radius: 10px;
          overflow: hidden;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          margin-bottom: 20px;
          position: relative;
          cursor: pointer;
          height: 280px;
          width: 100%;
          .image-container {
            position: relative;
            width: 25%;
            height: 100%;
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
            display: none;
          }

          .overlay .icon:hover {
            background-color: black;
            color: #fff;
          }
          .book-details {
            padding: 15px;
            text-align: left;
            width: 60%;
            .category {
              color: #666;
              font-size: 14px;
              margin-bottom: 5px;
            }

            h3 {
              font-size: 18px;
              margin: 5px 0;
            }

            .author {
              color: #999;
              font-size: 14px;
              margin-bottom: 10px;
            }

            .price {
              font-size: 16px;
              font-weight: bold;
              color: #e55a5a;

              span {
                color: #999;
                text-decoration: line-through;
                margin-right: 20px;
              }
            }
            .detail {
              font-size: 12.5px;
              width: 520px;
              color: #999;
            }
            button {
              width: 40px;
              height: 40px;

              background-color: black;
              border: none;
              border-radius: 2px;
              cursor: pointer;
              i {
                color: white;
                &::after {
                  content: "Xem giỏ hàng";
                  position: absolute;
                  top: -30px;
                  left: 0;
                  opacity: 0;
                  font-size: 9px;
                  width: 90px;
                  height: 20px;
                  background-color: black;
                  color: white;
                  text-align: center;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  transition: all 0.3s ease-in-out;
                  border-radius: 2px;
                  font-family: "Noto Sans";
                }
                &:hover {
                  &::after {
                    display: flex;
                    opacity: 1;
                  }
                }
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
}
</style>
