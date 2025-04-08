import pytest
import time
import os
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

class TestAddService:
    def setup_method(self, method):
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 15)

    def teardown_method(self, method):
        self.driver.quit()

    def test_addservice(self):
        driver = self.driver
        wait = self.wait

        # Step 1: Open website
        driver.get("http://localhost/coffeeduplicate/index.php")

        # Step 2: Login
        wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".nav-item:nth-child(6) span:nth-child(2)"))).click()
        wait.until(EC.visibility_of_element_located((By.NAME, "email"))).send_keys("serenitystyles.online@gmail.com")
        wait.until(EC.visibility_of_element_located((By.NAME, "password"))).send_keys("Serenity@123")
        wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, ".btn"))).click()

        # Step 3: Navigate to Services
        wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Services"))).click()
        wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "a > .btn"))).click()

        # Step 4: Fill Service Form
        wait.until(EC.visibility_of_element_located((By.ID, "service_name"))).send_keys("hair coloring")

        # Step 5: Add New Category
        wait.until(EC.element_to_be_clickable((By.ID, "openModalBtn"))).click()
        wait.until(EC.visibility_of_element_located((By.ID, "new_category_name"))).send_keys("coloring")
        wait.until(EC.element_to_be_clickable((By.ID, "submit_category"))).click()

        # Wait for modal to disappear (replace ID if different)
        try:
            wait.until(EC.invisibility_of_element_located((By.ID, "categoryModal")))
        except:
            pass

        # Step 6: Fill Remaining Fields
        wait.until(EC.visibility_of_element_located((By.NAME, "service_description"))).send_keys("give a fantastic look.")
        wait.until(EC.visibility_of_element_located((By.ID, "service_price"))).send_keys("560")

        # Step 7: Upload Image
        image_path = "C:\\xampp\\htdocs\\coffeeduplicate\\bbluntshampoo1.png"
        assert os.path.exists(image_path), f"Image file not found: {image_path}"
        wait.until(EC.presence_of_element_located((By.NAME, "service_image"))).send_keys(image_path)

        # Step 8: Submit Form
        submit_btn = wait.until(EC.element_to_be_clickable((By.CSS_SELECTOR, "button[type='submit']")))
        driver.execute_script("arguments[0].scrollIntoView({block: 'center'});", submit_btn)
        time.sleep(1)  # Ensure it's ready

        try:
            submit_btn.click()
        except:
            driver.execute_script("arguments[0].click();", submit_btn)

        # Optional: Wait for success notification/toast
        try:
            wait.until(EC.invisibility_of_element_located((By.CLASS_NAME, "toast")))
        except:
            pass
