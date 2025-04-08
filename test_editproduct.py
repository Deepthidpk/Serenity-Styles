import pytest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class TestEditProduct:
    def setup_method(self, method):
        """Setup WebDriver before each test"""
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 10)

    def teardown_method(self, method):
        """Quit WebDriver after each test"""
        self.driver.quit()

    def test_editproduct(self):
        """Test editing an existing product"""
        self.driver.get("http://localhost/coffeeduplicate/index.php")

        # Click login
        login_button = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".nav-item:nth-child(6) span:nth-child(2)")))
        login_button.click()

        # Enter email
        email_field = self.wait.until(EC.visibility_of_element_located((By.NAME, "email")))
        email_field.send_keys("serenitystyles.online@gmail.com")

        # Enter password
        password_field = self.wait.until(EC.visibility_of_element_located((By.NAME, "password")))
        password_field.send_keys("Serenity@123")

        # Click login button
        login_btn = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".btn")))
        login_btn.click()

        # Navigate to Products
        products_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Products")))
        products_link.click()

        # Click on Edit (Assumes the first visible edit link — adjust selector if needed)
        edit_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Edit")))
        edit_link.click()

        # Change product price
        price_input = self.wait.until(EC.visibility_of_element_located((By.NAME, "price")))
        price_input.clear()
        price_input.send_keys("340")

        # Upload new image (if image is optional, skip or mock it during test)
        image_input = self.wait.until(EC.presence_of_element_located((By.ID, "product_image")))
        image_path = "C:\\xampp\\htdocs\\coffeeduplicate\\bbluntshampoo1.png"  # Update with a valid image path on your system
        image_input.send_keys(image_path)

        # Submit the form
        submit_button = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button")))
        submit_button.click()

        # Optional: Assert success message or page change
        success_msg = self.wait.until(EC.presence_of_element_located((By.XPATH, "//div[contains(text(), 'Product updated successfully')]")))
        assert "Product updated successfully" in success_msg.text
