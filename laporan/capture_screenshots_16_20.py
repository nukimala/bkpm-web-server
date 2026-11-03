import os
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options

options = Options()
options.add_argument('--headless')
options.add_argument('--window-size=1280,800')
driver = webdriver.Chrome(options=options)

out_dir = 'c:/laragon/www/webserver/laporan/screenshots'
os.makedirs(out_dir, exist_ok=True)

# Acara 16 - RESTful API
driver.get('http://localhost/webserver/Acara16/si-akademik/public/api')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a16_api_info.png'))

driver.get('http://localhost/webserver/Acara16/si-akademik/public/api/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a16_api_all.png'))

driver.get('http://localhost/webserver/Acara16/si-akademik/public/api/mahasiswa/1')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a16_api_single.png'))

driver.get('http://localhost/webserver/Acara16/si-akademik/public/api/mahasiswa/99999')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a16_api_404.png'))

# Acara 17 - CRUD with validation
driver.get('http://localhost/webserver/Acara17/si-akademik/public/api/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a17_api_all.png'))

driver.get('http://localhost/webserver/Acara17/si-akademik/public/api/mahasiswa/1')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a17_api_single.png'))

# Acara 18 - JSON + HTTP status + CORS
driver.get('http://localhost/webserver/Acara18/si-akademik/public/api/mahasiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a18_api_all.png'))

driver.get('http://localhost/webserver/Acara18/si-akademik/public/api/mahasiswa/99999')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a18_api_404.png'))

# Acara 19 - Postman + auth
driver.get('http://localhost/webserver/Acara19/si-akademik/public/api/mahiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a19_api_all.png'))

driver.get('http://localhost/webserver/Acara19/si-akademik/public/api/mahiswa/99999')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a19_api_404.png'))

# Acara 20 - auth + error handling
driver.get('http://localhost/webserver/Acara20/si-akademik/public/api')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a20_api_info.png'))

driver.get('http://localhost/webserver/Acara20/si-akademik/public/api/mahiswa')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a20_api_all.png'))

driver.get('http://localhost/webserver/Acara20/si-akademik/public/api/mahiswa/99999')
time.sleep(1)
driver.save_screenshot(os.path.join(out_dir, 'a20_api_404.png'))

driver.quit()
print('All screenshots captured successfully!')
for f in sorted(os.listdir(out_dir)):
    if f.startswith('a1') or f.startswith('a2'):
        print(f, os.path.getsize(os.path.join(out_dir, f)))