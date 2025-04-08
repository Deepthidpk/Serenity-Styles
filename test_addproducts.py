# test_addproducts.py

import pytest
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class TestAddProducts:
    def setup_method(self, method):
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 70)

    def teardown_method(self, method):
        self.driver.quit()

    def test_addproducts(self):
        self.driver.get("http://localhost/coffeeduplicate/index.php")

        # Login Process
        login_btn = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".nav-item:nth-child(6) span:nth-child(2)")))
        login_btn.click()

        email_field = self.wait.until(EC.visibility_of_element_located((By.NAME, "email")))
        email_field.send_keys("serenitystyles.online@gmail.com")

        password_field = self.wait.until(EC.visibility_of_element_located((By.NAME, "password")))
        password_field.send_keys("Serenity@123")

        submit_login = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".btn")))
        submit_login.click()

        # Navigate to Products Section
        products_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Products")))
        products_link.click()

        add_product_btn = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a > .btn")))
        add_product_btn.click()

        # Fill Product Form
        product_name = self.wait.until(EC.visibility_of_element_located((By.ID, "product_name")))
        product_name.send_keys("bbbbblunt")

        # Add New Category
        open_modal_btn = self.wait.until(EC.element_to_be_clickable((By.ID, "openModalBtn")))
        open_modal_btn.click()

        new_category_name = self.wait.until(EC.visibility_of_element_located((By.ID, "new_category_name")))
        new_category_name.send_keys("cleansing cream")

        submit_category = self.wait.until(EC.element_to_be_clickable((By.ID, "submit_category")))
        submit_category.click()

        # Fill Other Details
        description = self.wait.until(EC.visibility_of_element_located((By.NAME, "pro_description")))
        description.send_keys("give fairness look.")

        quantity = self.wait.until(EC.visibility_of_element_located((By.NAME, "quantity")))
        quantity.send_keys("5")

        price = self.wait.until(EC.visibility_of_element_located((By.ID, "product_price")))
        price.send_keys("320")

        # Upload Image
        image_input = self.wait.until(EC.presence_of_element_located((By.NAME, "product_image")))
        image_input.send_keys("C:\\xampp\\htdocs\\coffeeduplicate\\bbluntshampoo1.png")

        # Wait for the button to be clickable and scroll into view
        submit_btn = self.wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button:nth-child(1)")))
        self.driver.execute_script("arguments[0].scrollIntoView(true);", submit_btn)
        time.sleep(20)  # small pause to ensure stability

        try:
            submit_btn.click()
        except Exception:
            # Fallback to JS click if regular click fails
            self.driver.execute_script("arguments[0].click();", submit_btn)

        # Optional: wait for toast or overlay to disappear before clicking the submit button
        try:
            self.wait.until(EC.invisibility_of_element_located((By.CLASS_NAME, "toast")))  # adjust selector if needed
        except:
            pass  # continue if toast not found
