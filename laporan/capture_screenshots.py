import os
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By

options = Options()
options.add_argument('--headless')
options.add_argument('--window-size=1280,800')
driver = webdriver.Chrome(options=options)

out_dir = 'c:/laragon/www/webserver/laporan/screenshots'
os.makedirs(out_dir, exist_ok=True)

# Acara 13
driver.get('http://localhost/webserver/Acara13/si-akademik/public/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a13_index.png'))

driver.get('http://localhost/webserver/Acara13/si-akademik/public/mahasiswa/create')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a13_create.png'))

try:
    btn = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
    btn.click()
    time.sleep(1)
    driver.save_screenshot(os.path.join(out_dir, 'a13_validation.png'))
except Exception as e:
    print('a13 error:', e)

# Acara 14
driver.get('http://localhost/webserver/Acara14/si-akademik/public/login')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a14_login.png'))

driver.get('http://localhost/webserver/Acara14/si-akademik/public/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a14_mahasiswa.png'))

driver.get('http://localhost/webserver/Acara14/si-akademik/public/mahasiswa/create')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a14_create.png'))

# Acara 15
driver.get('http://localhost/webserver/Acara15/si-akademik/public/api/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a15_api_all.png'))

driver.get('http://localhost/webserver/Acara15/si-akademik/public/api/mahasiswa?id=1')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a15_api_single.png'))

driver.get('http://localhost/webserver/Acara15/si-akademik/public/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a15_web.png'))

driver.quit()
print('All screenshots captured successfully!')
for f in os.listdir(out_dir):
    print(f, os.path.getsize(os.path.join(out_dir, f)))
