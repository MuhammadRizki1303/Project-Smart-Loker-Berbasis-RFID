import pandas as pd
import numpy as np
import cv2 as cv
import requests

# Membaca file CSV
try:
    id_names = pd.read_csv('id-names.csv')
    id_names = id_names[['id', 'name']]
except Exception as e:
    print(f"Kesalahan saat membaca file CSV: {e}")
    exit()

# Memuat classifier dan model
faceClassifier = cv.CascadeClassifier('Classifiers/haarface.xml')
lbph = cv.face.LBPHFaceRecognizer_create(threshold=500)

try:
    lbph.read('Classifiers/TrainedLBPH.yml')
except Exception as e:
    print(f"Kesalahan saat membaca model LBPH: {e}")
    exit()

# Mengaktifkan kamera
camera = cv.VideoCapture(0)
if not camera.isOpened():
    print("Kesalahan: Kamera tidak dapat dibuka.")
    exit()

# URL untuk mengirim data login ke PHP
post_url = "http://localhost/Project-Smart-Loker-Berbasis-RFID/login.php"

# Loop untuk menangkap frame video hingga tombol 'q' ditekan
try:
    while cv.waitKey(1) & 0xFF != ord('q'):
        ret, img = camera.read()
        if not ret:
            print("Kesalahan: Frame tidak tertangkap.")
            break

        grey = cv.cvtColor(img, cv.COLOR_BGR2GRAY)
        faces = faceClassifier.detectMultiScale(grey, scaleFactor=1.1, minNeighbors=4)

        # Untuk setiap wajah yang terdeteksi
        for x, y, w, h in faces:
            faceRegion = grey[y:y + h, x:x + w]
            faceRegion = cv.resize(faceRegion, (220, 220))

            label, _ = lbph.predict(faceRegion)
            try:
                name = id_names[id_names['id'] == label]['name'].item()

                # Kirim nama yang terdeteksi ke server PHP
                data = {"username": name}  # Password dihapus untuk keamanan
                response = requests.post(post_url, data=data, timeout=5)

                if response.ok:
                    print(f"Nama {name} berhasil dikirim untuk login.")
                else:
                    print(f"Gagal mengirim data ke server. Status: {response.status_code}, Pesan: {response.text}")

                cv.rectangle(img, (x, y), (x + w, y + h), (0, 0, 255), 2)
                cv.putText(img, name, (x, y + h + 30), cv.FONT_HERSHEY_COMPLEX, 1, (0, 0, 255))
            except Exception as e:
                print(f"Kesalahan saat mengambil nama untuk label {label}: {e}")
                pass

        cv.imshow('Recognize', img)

except KeyboardInterrupt:
    print("Pengenalan dihentikan oleh pengguna.")
finally:
    camera.release()
    cv.destroyAllWindows()
    print("Kamera dilepaskan dan semua jendela ditutup.")
