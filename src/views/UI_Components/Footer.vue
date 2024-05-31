<template>
  <footer>
    <div class="footer-container">
      <div class="footer-left">
        <h2>BOOKAPEE</h2>
        <p>Premium Bookstore theme</p>
        <address>
          <p>180 Cao Lỗ , Phường 4 , Quận 8 , Thành Phố Hồ Chí Minh</p>
          <p>
            <a href="mailto:dh52003670@student.stu.edu.com"
              >dh52003670@student.stu.edu.com</a
            >
          </p>
          <p><a href="tel:+1234567890">0854219725</a></p>
        </address>
      </div>
      <div class="footer-center">
        <div class="collections">
          <h3>BỘ SƯU TẬP</h3>
          <ul>
            <li>Lãng mạn</li>
            <li>Đạo đức kinh doanh</li>
            <li>Ly kỳ</li>
            <li>Tiếp thị</li>
            <li>Ngân sách và Tài chính</li>
          </ul>
        </div>
        <div class="quick-links">
          <h3>LIÊN KẾT NHANH</h3>
          <ul>
            <li>Về Chúng Tôi</li>
            <li>Câu Hỏi Thường Gặp</li>
            <li>Hướng Dẫn Mua Sắm</li>
            <li>Địa Điểm Giao Hàng</li>
            <li>Chính Sách Hoàn Tiền và Trả Hàng</li>
          </ul>
        </div>
      </div>
      <div class="footer-right">
        <h3>ĐĂNG KÝ NHẬN THÔNG TIN MỚI NHẤT</h3>
        <p>
          Đăng ký nhận Bản tin để nhận tất cả thông tin về Sự kiện, Ưu đãi, Giảm
          giá và hơn thế nữa.
        </p>
        <form @submit.prevent="subscribe">
          <input
            type="email"
            placeholder="Enter Your Mail Here"
            v-model="email"
          />
          <button type="submit"><i class="pi pi-send"></i></button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2022 Bookapee. All Rights Reserved.</p>
    </div>
  </footer>
</template>

<script>
export default {
  data() {
    return {
      email: "",
    };
  },
  methods: {
    subscribe() {
      // handle subscription logic here
      alert(`Subscribed with email: ${this.email}`);
    },
  },
};
</script>

<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto Sans:wght@400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap");

footer {
  background-color: #121823;
  color: #ffffff;
  padding: 40px 0;
  height: 60vh;
  font-family: "Noto Sans";
  .footer-container {
    display: flex;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    height: 90%;
    color: #c5cfd6;
    .footer-left {
      max-width: 300px;
      height: 60%;
      display: flex;
      justify-content: space-between;
      flex-direction: column;
      h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: white;
      }

      p,
      address {
        margin: 5px 0;
      }

      address p {
        margin: 5px 0;
      }
    }

    .footer-center {
      display: flex;
      height: 100%;
      width: 45%;
      > div {
        margin-right: 50px;
        height: 100%;
        width: 50%;
        h3 {
          font-size: 18px;
          margin-bottom: 20px;
          color: white;
        }

        ul {
          list-style-type: none;
          padding: 0;
          margin: 0;
          height: 75%;
          display: flex;
          justify-content: space-between;
          flex-direction: column;
          li {
            margin: 5px 0;
          }
        }
      }
    }

    .footer-right {
      max-width: 300px;
      height: 70%;
      display: flex;
      justify-content: space-between;
      flex-direction: column;
      h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: white;
      }

      form {
        display: flex;

        input[type="email"] {
          padding: 15px;
          border: none;
          border-radius: 5px 0 0 5px;
          outline: none;
          flex: 1;
          &::placeholder {
            font-family: "Noto Sans";
            font-style: italic;
          }
        }

        button {
          padding: 20px;
          border: none;
          background-color: #ff6b6b;
          color: #ffffff;
          border-radius: 0 5px 5px 0;
          cursor: pointer;

          &:hover {
            background-color: #e55a5a;
          }
        }
      }
    }
  }

  .footer-bottom {
    text-align: center;
    margin-top: 20px;
    border-top: 1px solid #333333;
    padding-top: 20px;
    height: 15%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    background-color: #1d2330;
    p {
      margin: 0;
      position: relative;
      top: -10px;
      font-size: 12px;
      font-weight: bold;
    }
  }
}
</style>
