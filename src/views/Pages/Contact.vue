<template>
  <NavBar></NavBar>

  <div class="maps">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.9544102884747!2d106.67783209999999!3d10.737997199999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!2sSaigon%20Technology%20University!5e0!3m2!1sen!2s!4v1702184866735!5m2!1sen!2s"
      style="border: 0"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"
    ></iframe>
  </div>
  <div class="form">
    <div class="location">
      <div class="location-in">
        <p><i class="fa-solid fa-location-dot"></i> Our Location</p>
        <div>180 Đ. Cao Lỗ, Phường 4, Quận 8, Thành phố Hồ Chí Minh</div>
      </div>
      <div class="location-in">
        <p><i class="fa-regular fa-clock"></i>Hours of Operation</p>
        <div>
          Monday – Saturday : 8 AM – 6 PM <br />
          Sunday : Close
        </div>
      </div>
      <div class="location-in">
        <p><i class="fa-solid fa-mobile-screen-button"></i>Careers</p>
        <div>
          f you’re interested in employment opportunities at Digic, please email
          us: contact@yourstore.com
        </div>
      </div>
    </div>
    <div class="message">
      <p>Leave us a Message</p>
      <h5>Your message</h5>
      <textarea name="" id="" cols="75" rows="20"></textarea>
      <button>Send</button>
    </div>
  </div>
  <Footer></Footer>
</template>

<script>
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";
export default {
  components: { NavBar, Footer, Route },
};
</script>

<style>
@import "@/assets/styles/contact.scss";
</style>
