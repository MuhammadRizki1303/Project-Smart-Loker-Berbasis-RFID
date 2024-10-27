import pandas as pd        # Mengimpor pandas untuk manipulasi data
import numpy as np         # Mengimpor numpy untuk operasi numerik
import cv2 as cv           # Mengimpor OpenCV untuk pengolahan gambar dan video

# Membaca file CSV yang berisi ID dan nama
id_names = pd.read_csv('id-names.csv')
# Memilih hanya kolom 'id' dan 'name' dari data
id_names = id_names[['id', 'name']]

# Memuat model klasifikasi wajah menggunakan file XML
faceClassifier = cv.CascadeClassifier('Classifiers/haarface.xml')

# Membuat model pengenal wajah LBPH dengan ambang kepercayaan 500
lbph = cv.face.LBPHFaceRecognizer_create(threshold=500)
# Membaca model LBPH yang sudah dilatih dari file YML
lbph.read('Classifiers/TrainedLBPH.yml')

# Mengaktifkan kamera
camera = cv.VideoCapture(0)

# Memulai loop untuk menangkap frame video hingga tombol 'q' ditekan
while cv.waitKey(1) & 0xFF != ord('q'):
    _, img = camera.read()                         # Membaca frame dari kamera
    grey = cv.cvtColor(img, cv.COLOR_BGR2GRAY)     # Mengonversi gambar ke grayscale

    # Mendeteksi wajah dalam frame menggunakan classifier
    faces = faceClassifier.detectMultiScale(grey, scaleFactor=1.1, minNeighbors=4)

    # Untuk setiap wajah yang terdeteksi
    for x, y, w, h in faces:
        # Mendapatkan area wajah dan mengubah ukurannya menjadi 220x220
        faceRegion = grey[y:y + h, x:x + w]
        faceRegion = cv.resize(faceRegion, (220, 220))

        # Memprediksi ID wajah dengan model LBPH dan mendapatkan nilai kepercayaan
        label, trust = lbph.predict(faceRegion)
        try:
            # Mencari nama berdasarkan ID yang diprediksi
            name = id_names[id_names['id'] == label]['name'].item()
            # Menggambar kotak persegi panjang di sekitar wajah
            cv.rectangle(img, (x, y), (x + w, y + h), (0, 0, 255), 2)
            # Menampilkan nama di bawah kotak wajah
            cv.putText(img, name, (x, y + h + 30), cv.FONT_HERSHEY_COMPLEX, 1, (0, 0, 255))
        except:
            pass  # Mengabaikan kesalahan jika nama tidak ditemukan

    # Menampilkan frame dengan wajah yang dikenali
    cv.imshow('Recognize', img)

# Melepaskan kamera dan menutup semua jendela OpenCV setelah loop selesai
camera.release()
cv.destroyAllWindows()
