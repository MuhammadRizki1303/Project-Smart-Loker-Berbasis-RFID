import os                          # Mengimpor modul os untuk bekerja dengan sistem file
import numpy as np                 # Mengimpor numpy untuk operasi numerik
import pandas as pd                # Mengimpor pandas untuk manipulasi data
import cv2 as cv                   # Mengimpor OpenCV untuk pengolahan gambar dan video
from datetime import datetime      # Mengimpor datetime untuk mendapatkan waktu saat ini

# Memeriksa apakah file id-names.csv ada
if os.path.exists('id-names.csv'):
    # Membaca file id-names.csv jika ada
    id_names = pd.read_csv('id-names.csv')
    id_names = id_names[['id', 'name']]  # Mengambil hanya kolom 'id' dan 'name'
else:
    # Membuat file baru dengan kolom 'id' dan 'name' jika file tidak ada
    id_names = pd.DataFrame(columns=['id', 'name'])
    id_names.to_csv('id-names.csv', index=False)

# Memeriksa apakah folder 'faces' ada, jika tidak membuat folder
if not os.path.exists('faces'):
    os.makedirs('faces')

print('Selamat Datang!')
print('\nTolong masukkan ID anda.')
print('jika ini pertama kali anda melakukan ini pilih nomor 1-10000 untuk ID anda')

# Meminta input ID dari pengguna
id = int(input('ID: '))
name = ''

# Memeriksa apakah ID sudah ada di file id-names.csv
if id in id_names['id'].values:
    # Mengambil nama berdasarkan ID yang ada
    name = id_names[id_names['id'] == id]['name'].item()
    print(f'Welcome Back {name}!!')
else:
    # Meminta input nama dari pengguna baru
    name = input('Please Enter your name: ')
    # Membuat folder untuk menyimpan gambar wajah pengguna berdasarkan ID
    os.makedirs(f'faces/{id}')
    # Menambahkan data ID dan nama ke file id-names.csv
    id_names = pd.concat([id_names, pd.DataFrame({'id': [id], 'name': [name]})], ignore_index=True)
    id_names.to_csv('id-names.csv', index=False)

print("\nLet's capture!")

print("Now this is where you begin taking photos. Once you see a rectangle around your face, press the 's' key to capture a picture.", end=" ")
print("It is recommended to take at least 20-25 pictures, from different angles, in different poses, with and without specs, you get the gist.")
input("\nPress ENTER to start when you're ready, and press the 'q' key to quit when you're done!")

# Mengaktifkan kamera
camera = cv.VideoCapture(0)
# Memuat model klasifikasi wajah menggunakan file XML
face_classifier = cv.CascadeClassifier('Classifiers/haarface.xml')

photos_taken = 0  # Variabel untuk menghitung jumlah foto yang diambil

# Memulai loop untuk menangkap gambar hingga tombol 'q' ditekan
while(cv.waitKey(1) & 0xFF != ord('q')):
    _, img = camera.read()                       # Membaca frame dari kamera
    grey = cv.cvtColor(img, cv.COLOR_BGR2GRAY)   # Mengonversi gambar ke grayscale
    # Mendeteksi wajah dalam frame menggunakan classifier
    faces = face_classifier.detectMultiScale(grey, scaleFactor=1.1, minNeighbors=5, minSize=(50, 50))
    for (x, y, w, h) in faces:
        # Menggambar kotak persegi panjang di sekitar wajah
        cv.rectangle(img, (x, y), (x + w, y + h), (0, 0, 255), 2)

        # Mendapatkan area wajah dalam grayscale
        face_region = grey[y:y + h, x:x + w]
        # Menyimpan foto saat tombol 's' ditekan dan area wajah cukup terang
        if cv.waitKey(1) & 0xFF == ord('s') and np.average(face_region) > 50:
            face_img = cv.resize(face_region, (220, 220))  # Mengubah ukuran gambar wajah menjadi 220x220
            img_name = f'face.{id}.{datetime.now().microsecond}.jpeg'  # Membuat nama file gambar dengan ID dan waktu
            cv.imwrite(f'faces/{id}/{img_name}', face_img)  # Menyimpan gambar wajah ke folder pengguna
            photos_taken += 1  # Menambah jumlah foto yang diambil
            print(f'{photos_taken} -> Photos taken!')

    cv.imshow('Face', img)  # Menampilkan frame dengan kotak wajah yang terdeteksi

# Melepaskan kamera dan menutup semua jendela OpenCV setelah loop selesai
camera.release()
cv.destroyAllWindows()
