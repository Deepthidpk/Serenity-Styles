# Generated by Selenium IDE
import pytest
import time
import json
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support import expected_conditions
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities

class TestPayment():
  def setup_method(self, method):
    self.driver = webdriver.Chrome()
    self.vars = {}
  
  def teardown_method(self, method):
    self.driver.quit()
  
  def wait_for_window(self, timeout = 2):
    time.sleep(round(timeout / 1000))
    wh_now = self.driver.window_handles
    wh_then = self.vars["window_handles"]
    if len(wh_now) > len(wh_then):
      return set(wh_now).difference(set(wh_then)).pop()
  
  def test_payment(self):
    self.driver.get("http://localhost/coffeeduplicate/index.php")
    self.driver.set_window_size(1296, 688)
    self.driver.find_element(By.CSS_SELECTOR, ".nav-item:nth-child(6) span:nth-child(2)").click()
    self.driver.find_element(By.NAME, "email").click()
    self.driver.find_element(By.NAME, "email").send_keys("deepthicdgenai2024@gmail.com")
    self.driver.find_element(By.NAME, "password").click()
    self.driver.find_element(By.NAME, "password").send_keys("Deepthi@111")
    self.driver.find_element(By.CSS_SELECTOR, ".btn").click()
    self.driver.find_element(By.CSS_SELECTOR, ".nav-item:nth-child(4) > .nav-link").click()
    self.driver.find_element(By.CSS_SELECTOR, ".nav-item:nth-child(7) > .nav-link").click()
    self.driver.find_element(By.CSS_SELECTOR, ".btn").click()
    self.driver.find_element(By.ID, "firstname").click()
    self.driver.find_element(By.ID, "firstname").send_keys("Eza Anoop")
    self.driver.find_element(By.ID, "state").click()
    dropdown = self.driver.find_element(By.ID, "state")
    dropdown.find_element(By.XPATH, "//option[. = 'Kerala']").click()
    self.driver.find_element(By.ID, "state").click()
    self.driver.find_element(By.ID, "district").click()
    self.driver.find_element(By.ID, "district").send_keys("malappuram")
    self.driver.find_element(By.ID, "address").click()
    self.driver.find_element(By.ID, "address").send_keys("valapra")
    self.driver.find_element(By.ID, "towncity").click()
    self.driver.find_element(By.ID, "towncity").send_keys("nilambur")
    self.driver.find_element(By.ID, "postcodezip").click()
    self.driver.find_element(By.ID, "postcodezip").send_keys("679334")
    self.driver.find_element(By.ID, "phone").click()
    self.driver.find_element(By.ID, "phone").send_keys("8590918598")
    self.driver.find_element(By.ID, "rzp-button").click()
    self.driver.switch_to.frame(0)
    self.driver.find_element(By.NAME, "card.number").click()
    self.driver.find_element(By.NAME, "card.number").send_keys("4111 1111 1111 1111")
    self.driver.find_element(By.NAME, "card.expiry").click()
    self.driver.find_element(By.NAME, "card.expiry").send_keys("12 / 25")
    self.driver.find_element(By.NAME, "card.cvv").click()
    self.driver.find_element(By.NAME, "card.cvv").send_keys("123")
    self.driver.find_element(By.NAME, "email").click()
    self.driver.find_element(By.NAME, "email").send_keys("deepthicdgenai2024@gmail.com")
    self.driver.find_element(By.NAME, "save").click()
    self.driver.find_element(By.NAME, "button").click()
    self.vars["window_handles"] = self.driver.window_handles
    self.driver.find_element(By.CSS_SELECTOR, ".only\\3Am-auto:nth-child(1)").click()
    self.vars["win9941"] = self.wait_for_window(2000)
    self.vars["root"] = self.driver.current_window_handle
    self.driver.switch_to.window(self.vars["win9941"])
    self.driver.close()
    self.driver.switch_to.window(self.vars["root"])
    self.driver.switch_to.frame(0)
    self.driver.find_element(By.CSS_SELECTOR, ".border-2").send_keys("1234")
    self.driver.find_element(By.CSS_SELECTOR, ".left-0 > .rounded-lg").click()
    self.driver.switch_to.default_content()
    assert self.driver.switch_to.alert.text == "Payment Successful! Order Placed."
  
