<template>
  <NavBar></NavBar>
  <div class="container" v-if="currentUser.maVaiTro === '1'">
    <div
      class="cover-image"
      :style="{
        backgroundImage: `url(http://localhost/LVTN/book-store/src/api/${userData.anhbia})`,
        backgroundColor: !getUserCover ? ' #f18a81' : 'transparent',
      }"
    >
      <div class="co">
        <label
          for="coverChange"
          @mouseover="handleMouseOver"
          @mouseleave="handleMouseLeave"
          ><i class="fa-solid fa-pen-to-square"></i>
        </label>

        <input type="file" id="coverChange" @change="handleCoverImageUpload" />
        <button @click="uploadFileCover">Upload</button>
      </div>
      <div class="remind" v-if="isHovered">
        Sau khi chọn ảnh nhớ ấn nút Upload và ấn cập nhật để lưu lại ảnh nhé bạn
        ơi :3
      </div>
    </div>
    <div class="profile">
      <div class="profile-content">
        <div class="left-side">
          <div class="ass">
            <h4>Tài khoản của tôi</h4>
            <button @click="updateProfile">Cập nhật</button>
          </div>

          <form @submit.prevent="updateProfile">
            <h3>Thông tin tài khoản</h3>
            <div class="user-info">
              <div class="info">
                <p>Tên đăng nhập</p>
                <input
                  type="text"
                  name=""
                  :placeholder="userData.taikhoan"
                  disabled
                />
              </div>
              <div class="info">
                <p>Địa chỉ email</p>
                <input
                  type="text"
                  name=""
                  v-model="email"
                  disabled
                  v-if="info && info.email"
                />
                <router-link class="back" to="/changeMail"
                  >Đổi địa chỉ email <i class="fa-solid fa-arrow-right"></i
                ></router-link>
              </div>
              <div class="info">
                <p>Họ và tên</p>
                <input type="text" name="" v-model="userData.tenKH" />
              </div>
              <div class="info">
                <p>Số điện thoại</p>
                <input type="text" name="" v-model="userData.sdt" disabled />
                <router-link to="/changePhone"
                  >Đổi số điện thoại <i class="fa-solid fa-arrow-right"></i
                ></router-link>
              </div>
            </div>
            <div class="user-contact">
              <h3 class="add">Địa chỉ liên hệ</h3>
              <div class="address">
                <p>Địa chỉ của tôi</p>
                <input type="text" name="" v-model="userData.diachi" />
              </div>
              <div class="address2">
                <h3 class="add">Đổi mật khẩu</h3>
                <div class="zz">
                  <p>Nhập mật khẩu cũ</p>
                  <input
                    type="password"
                    name=""
                    v-model="currentPassword"
                    @input="checkPass"
                  />
                </div>

                <p>Nhập mật khẩu mới</p>
                <input
                  type="password"
                  name=""
                  v-model="newPassword"
                  :disabled="disabled"
                  placeholder="Mật khẩu tối thiểu 8 chữ số và có ít nhất 1 kí tự đặc biệt"
                />
                <button class="changepass" @click.prevent="handleChangePass">
                  Đổi mật khẩu
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="right-side">
          <div
            class="avatar"
            :style="{
              backgroundImage: `url('http://localhost/LVTN/book-store/src/api/${userData.hinh}')`,

              backgroundColor: !getAvatarUser ? '#f2f2f2' : 'transparent',
            }"
          ></div>
          <div class="descrip">
            <div class="co">
              <button>
                <label
                  for="coverChange1"
                  @mouseover="handleMouseOver1"
                  @mouseleave="handleMouseLeave1"
                >
                  Chọn ảnh
                </label>
              </button>
              <input
                type="file"
                id="coverChange1"
                @change="handleAvatarImageUpload"
              />
              <button @click="uploadFileAvatar">Upload</button>
            </div>
          </div>
          <div class="des">
            <span>
              <b>Hướng dẫn</b> <br />
              <span class="guide">
                Sau khi câp nhật các thông tin trong hồ sơ (Ảnh bìa, Tên ,
                email, vv...) , nhớ ấn nút cập nhật để lưu lại thông tin bạn nhé
                :3
              </span>
            </span>
          </div>
          <div class="remind" v-if="isHovered1">
            Sau khi chọn ảnh nhớ ấn nút Upload và ấn cập nhật để lưu lại ảnh nhé
            bạn ơi :3
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import NavBar from "@/views/UI_Components/NavBar.vue";

export default {
  components: {
    NavBar,
  },
  setup() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser")) || {};
    const userData = ref({ ...currentUser });

    const updateProfile = () => {
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/updateProfile.php",
          userData.value
        )
        .then((res) => {
          if (res.data.updateProfileSuccess) {
            localStorage.setItem("currentUser", JSON.stringify(userData.value));
            alert("Cập nhật thông tin thành công");
            window.location.reload();
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };
    const getUserCover = ref("");

    const getCover = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/cover.php", {
          user: userData.value.maND,
        })
        .then((res) => {
          if (res.data.user) {
            getUserCover.value = res.data.user.anhbia;
            userData.value.anhbia = getUserCover.value;
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };
    const getAvatarUser = ref("");

    const getAvatar = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/cover.php", {
          user: userData.value.maND,
        })
        .then((res) => {
          if (res.data.user) {
            getAvatarUser.value = res.data.user.hinh;
            userData.value.hinh = getAvatarUser.value;
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };

    const file = ref("");
    const handleCoverImageUpload = (event) => {
      file.value = event.target.files[0];
    };
    const handleAvatarImageUpload = (event) => {
      file.value = event.target.files[0];
    };
    const uploadFileCover = () => {
      const maND = userData.value.maND;
      let formData = new FormData();
      formData.append("file", file.value);
      formData.append("maND", maND);
      file.value = "";
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/uploadImage.php",
          formData,

          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          if (!response.data) {
            alert("Upload hình không thành công");
          } else {
            alert("Upload thành công");

            getCover();
          }
        })
        .catch((error) => {
          console.error(error);
        });
    };
    const uploadFileAvatar = () => {
      const maND = userData.value.maND;
      let formData = new FormData();
      formData.append("file", file.value);
      formData.append("maND", maND);
      file.value = "";
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/uploadImage2.php",
          formData,

          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          if (!response.data) {
            alert("Upload hình không thành công");
          } else {
            alert("Upload thành công");
            getAvatar();
          }
        })
        .catch((error) => {
          console.error(error);
        });
    };
    const isHovered = ref(false);
    const handleMouseOver = () => {
      isHovered.value = true;
    };

    const handleMouseLeave = () => {
      isHovered.value = false;
    };
    const isHovered1 = ref(false);
    const handleMouseOver1 = () => {
      isHovered1.value = true;
    };

    const handleMouseLeave1 = () => {
      isHovered1.value = false;
    };
    const currentPassword = ref("");
    const newPassword = ref("");

    const passwordLengthValid = computed(() => newPassword.value.length >= 8);
    const passwordSpecialCharValid = computed(() =>
      /[!@#$%^&*(),.?":{}|<>]/.test(newPassword.value)
    );
    // const router = useRouter();

    const handleChangePass = () => {
      if (passwordLengthValid.value && passwordSpecialCharValid.value) {
        axios
          .post("http://localhost/LVTN/book-store/src/api/changepass.php", {
            maND: userData.value.maND,
            newPassword: newPassword.value,
          })
          .then((response) => {
            if (response.data.changePassSuccess) {
              alert("Cập nhật mật khẩu thành công!");
              localStorage.setItem(
                "currentUser",
                JSON.stringify(userData.value)
              );
              window.location.reload();
            } else {
              alert("Đổi mật khẩu thất bại");
            }
          })
          .catch((err) => {
            console.log("Error", err);
          });
      } else {
        alert(
          "Vui lòng nhập mật khẩu tối thiểu 8 kí tự, trong đó có ít nhất 1 kí tự đặc biệt"
        );
      }
    };
    const disabled = ref("false");

    const checkPass = () => {
      if (currentPassword.value.length == info.value.matkhau.length) {
        if (currentPassword.value != info.value.matkhau) {
          disabled.value = true;

          alert("Mật khẩu bạn nhập không đúng!!");
        } else {
          disabled.value = false;
        }
      } else if (currentPassword.value.length > info.value.matkhau.length) {
        alert("Mật khẩu bạn nhập không đúng!!");
        disabled.value = true;
      }
    };
    const info = ref();
    onMounted(() => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/cover.php", {
          user: userData.value.maND,
        })
        .then((res) => {
          if (res.data.user) {
            info.value = res.data.user;
            console.log(info.value);
          }
        })
        .catch((err) => console.log("error", err));
    });
    const email = computed(() => info.value.email);
    return {
      currentUser,
      userData,
      updateProfile,
      handleCoverImageUpload,
      handleAvatarImageUpload,
      file,
      uploadFileCover,
      uploadFileAvatar,
      getUserCover,
      getAvatarUser,
      isHovered,
      handleMouseOver,
      handleMouseLeave,
      isHovered1,
      handleMouseOver1,
      handleMouseLeave1,
      currentPassword,
      newPassword,
      handleChangePass,
      passwordLengthValid,
      passwordSpecialCharValid,
      checkPass,
      disabled,
      info,
      email,
    };
  },
};
</script>
<style lang="scss" scoped>
@import "@/assets/styles/profile.scss";
</style>
