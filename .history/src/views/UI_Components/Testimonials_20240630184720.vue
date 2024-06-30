<template>
  <section class="testimonials">
    <div class="container">
      <div class="header">
        <h2>Đánh giá của khách hàng</h2>
        <p>
          Mang đến bàn là những chiến lược sinh tồn đôi bên có lợi để đảm bảo sự
          thống trị chủ động. Đến cuối ngày, điều quan trọng là điều hướng tiếp
          theo, một bình thường mới đã phát triển từ thế hệ X đang trên đường
          băng tiến về một giải pháp đám mây được tối ưu hóa. Tận dụng những cơ
          hội dễ dàng để xác định một hoạt động có giá trị gần như một giải pháp
          thử nghiệm.
        </p>
      </div>
      <div class="testimonial-cards">
        <div
          class="testimonial-card"
          v-for="testimonial in testimonials"
          :key="testimonial.id"
        >
          <div class="profile">
            <img :src="testimonial.image" :alt="testimonial.name" />
            <div class="profile-info">
              <h3>{{ testimonial.name }}</h3>
              <p class="role">{{ testimonial.role }}</p>
            </div>
          </div>
          <p class="comment">{{ testimonial.comment }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      testimonials: [
        {
          id: 1,
          image: require("@/assets/images/bg.jpg"),
          name: "Henry",
          role: "Trưởng Nhóm",
          comment:
            "Tôi rất ấn tượng với sự chuyên nghiệp và dịch vụ tốt của Bookapee. Họ mang đến cho tôi những cuốn sách tuyệt vời và giá trị.",
        },
        {
          id: 2,
          image: require("@/assets/images/home-bg.jpg"),
          name: "Jamie",
          role: "Nhà Thiết Kế Chính",
          comment:
            "Tôi đã mua sách từ Bookapee và rất hài lòng với trải nghiệm của mình. Dịch vụ vận chuyển miễn phí toàn cầu thật sự tuyệt vời.",
        },
        // Thêm thêm phản hồi nếu cần
      ],
    };
  },
};
</script>
<style lang="scss" scoped>
@import url("https://fonts.googleapis.com/css2?family=Noto Sans:wght@400;500;700&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap");
.testimonials {
  background-color: #121823; // Adjust to match your actual background color
  color: #fff;
  padding: 60px 20px;
  height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: "Noto Sans";
  .container {
    width: 70%;
    height: 90%;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    .header {
      margin-bottom: 40px;
      width: 35%;

      h2 {
        font-size: 36px;
        font-weight: bold;
      }

      p {
        color: #b3b3b3;
        margin-top: 10px;
        margin-bottom: 30px;
        line-height: 1.6;
      }
    }

    .testimonial-cards {
      display: flex;
      gap: 20px;
      width: 58%;
      height: 100%;
      .testimonial-card {
        background-color: #1d2530;
        border-radius: 12px;
        padding: 20px;
        width: calc(50% - 10px);
        height: 100%;
        .profile {
          display: flex;
          align-items: center;
          margin-bottom: 15px;
          border-bottom: 1px solid #a6a6a6;
          img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
          }

          .profile-info {
            h3 {
              margin: 0;
              font-size: 18px;
            }

            .role {
              font-size: 14px;
              color: #a6a6a6;
            }
          }
        }

        .comment {
          color: #b3b3b3;
          line-height: 1.6;
        }
      }
    }
  }
}
</style>
