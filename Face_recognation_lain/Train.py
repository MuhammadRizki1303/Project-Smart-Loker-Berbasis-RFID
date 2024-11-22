import os                      # Mengimpor modul os untuk bekerja dengan sistem file
import pandas as pd            # Mengimpor pandas untuk manipulasi data
import numpy as np             # Mengimpor numpy untuk operasi numerik
import cv2 as cv               # Mengimpor OpenCV untuk pengolahan gambar

# Membaca file id-names.csv untuk mendapatkan ID dan nama
id_names = pd.read_csv('id-names.csv')
id_names = id_names[['id', 'name']]  # Memilih hanya kolom 'id' dan 'name'

# Membuat model pengenal wajah LBPH dengan ambang batas kepercayaan 500
lbph = cv.face.LBPHFaceRecognizer_create(threshold=500)

# Fungsi untuk mengumpulkan data wajah dan label ID
def create_train():
    faces = []      # List untuk menyimpan data wajah
    labels = []     # List untuk menyimpan label ID yang sesuai dengan data wajah
    # Mengiterasi setiap folder ID dalam folder 'faces'
    for id in os.listdir('faces'):
        path = os.path.join('faces', id)  # Membuat path untuk folder ID
        try:
            os.listdir(path)  # Memeriksa apakah folder tersebut ada
        except:
            continue  # Lewati jika folder tidak dapat dibaca
        # Mengiterasi setiap gambar dalam folder ID
        for img in os.listdir(path):
            try:
                # Membaca gambar dan mengonversinya ke grayscale
                face = cv.imread(os.path.join(path, img))
                face = cv.cvtColor(face, cv.COLOR_BGR2GRAY)

                # Menambahkan gambar wajah dan ID ke dalam list
                faces.append(face)
                labels.append(int(id))
            except:
                pass  # Lewati gambar jika terjadi kesalahan
    # Mengembalikan data wajah dan label dalam bentuk array numpy
    return np.array(faces), np.array(labels)

# Memanggil fungsi untuk mendapatkan data wajah dan label ID
faces, labels = create_train()

print('Pelatihan Model Dimulai')  # Memberi tahu bahwa proses pelatihan dimulai
# Melatih model LBPH dengan data wajah dan label
lbph.train(faces, labels)
# Menyimpan model yang sudah dilatih ke file YML
lbph.save('Classifiers/TrainedLBPH.yml')
print('Pelatihan Model Selesai')  # Memberi tahu bahwa proses pelatihan selesai
