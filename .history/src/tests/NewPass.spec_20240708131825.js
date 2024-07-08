import { shallowMount } from "@vue/test-utils";
import axios from "axios";
import Register from "@/components/Register.vue";

// Mock Axios globally
jest.mock("axios");

describe("Register.vue", () => {
  it("Check post data to backend, send new password", async () => {
    // Mock Axios post method to simulate a successful response
    axios.post.mockResolvedValueOnce({ status: 200 });

    // Mount the component
    const wrapper = shallowMount(Register);

    // Set email input value
    const emailInput = wrapper.find('input[type="email"]');
    await emailInput.setValue("test@example.com");

    // Trigger button click that invokes the axios post request
    await wrapper.find("button").trigger("click");

    // Wait for Vue component to update after axios request
    await wrapper.vm.$nextTick();

    // Assert axios post request was called with the correct URL and data
    expect(axios.post).toHaveBeenCalledWith(
      "http://localhost/LVTN/book-store/src/api/newpass.php",
      { email: "test@example.com" }
    );

    // Assert component's behavior or state after successful request (if applicable)
    // For example, check if a success message is displayed or a callback function is called

    // Example assertion for status check
    // expect(wrapper.vm.statusMessage).toBe('Password reset email sent!');
  });
});
