<template>
  <section class="featured-books">
    <div class="container">
      <div class="header">
        <span class="badge">MỚI NHẤT</span>
        <h2>Sản phẩm mới</h2>
        <p>Đọc để phát triển kỹ nằng giao tiếp</p>
      </div>
      <div class="book-list">
        <router-link
          to="/products"
          class="book"
          v-for="book in books"
          :key="book.id"
          @mouseover="hover = book.id"
          @mouseleave="hover = null"
        >
          <div class="image-container">
            <img :src="book.image" :alt="book.title" />
            <transition name="fade">
              <div v-if="hover === book.id" class="overlay">
                <div class="icon"><i class="pi pi-eye"></i></div>
                <div class="icon"><i class="pi pi-heart"></i></div>
                <div class="icon"><i class="pi pi-shopping-cart"></i></div>
              </div>
            </transition>
          </div>
          <div class="book-details">
            <span class="category">{{ book.category }}</span>
            <h3>{{ book.title }}</h3>
            <p class="author">Tác giả: {{ book.author }}</p>
            <p class="price">{{ book.price }}</p>
          </div>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      hover: null,
      books: [
        {
          id: 1,
          image: require("@/assets/images/book5.jpg"),
          category: "Tiểu sử, Lối sống",
          title: "Celebration of Freedom - 2 of Freedom",
          author: "Ellie Thomson",
          price: "$15.00",
        },
        {
          id: 2,
          image: require("@/assets/images/book6.jpg"),
          category: "Tiểu sử, Lối sống",
          title: "Master of Photography  – 2 Series Bundle",
          author: "Ellie Thomson",
          price: "$32.00",
        },
        {
          id: 3,
          image: require("@/assets/images/book7.jpg"),
          category: "Động vật hoang dã",
          title: "Wildlife Photography Tips & Techniques",
          author: "Ellie Thomson, Harry",
          price: "$16.00",
        },
        {
          id: 4,
          image: require("@/assets/images/book8.jpg"),
          category: "Nghệ thuật & Nhiếp ảnh, Lối sống",
          title: "The Inner Face: A Guide to the Emotions We Hide",
          author: "Janet",
          price: "$18.00",
        },
      ],
    };
  },
};
</script>
<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto Sans:wght@400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap");
.featured-books {
  text-align: center;
  padding: 40px 20px;
  font-family: "Noto Sans";
  margin-bottom: 50px;
  .container {
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
      justify-content: space-between;
      flex-wrap: wrap;
      a {
        text-decoration: none;
        color: #666;
      }
      .book {
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
          height: 100%;
          background: rgba(#dd907b, 0.7);
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 10px;
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
            font-size: 13.5px;
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
