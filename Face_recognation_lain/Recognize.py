import pandas as pd
import cv2 as cv

# Load model dan data id-names.csv
try:
    id_names = pd.read_csv('id-names.csv')
    id_names = id_names[['id', 'name']]
except Exception as e:
    print(f"Kesalahan saat membaca file CSV: {e}")
    exit()

faceClassifier = cv.CascadeClassifier('Classifiers/haarface.xml')

try:
    lbph = cv.face.LBPHFaceRecognizer_create(threshold=500)
    lbph.read('Classifiers/TrainedLBPH.yml')
except Exception as e:
    print(f"Kesalahan saat membaca model LBPH: {e}")
    exit()

# Buka kamera
camera = cv.VideoCapture(0)
if not camera.isOpened():
    print("Kesalahan: Kamera tidak dapat dibuka.")
    exit()

# Ambil username dari input pengguna
username = "Rizki"  # Ganti dengan cara yang sesuai untuk mendapatkan username

# Loop hingga pengguna menekan 'q' untuk keluar
while True:
    ret, img = camera.read()
    if not ret:
        print("Kesalahan: Tidak ada frame yang ditangkap dari kamera.")
        break

    grey = cv.cvtColor(img, cv.COLOR_BGR2GRAY)
    faces = faceClassifier.detectMultiScale(grey, scaleFactor=1.1, minNeighbors=4)

    if len(faces) == 0:
        cv.putText(img, "Tidak ada wajah yang terdeteksi", (10, 30), cv.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)
    else:
        for x, y, w, h in faces:
            faceRegion = grey[y:y + h, x:x + w]
            faceRegion = cv.resize(faceRegion, (220, 220))

            label, confidence = lbph.predict(faceRegion)
            try:
                recognized_name = id_names[id_names['id'] == label]['name'].item()
                print(f"Nama yang dikenali: {recognized_name}, Confidence: {confidence}")

                # Cek apakah nilai kepercayaan dalam batas yang dapat diterima
                if confidence < 100:  # Sesuaikan ambang batas jika perlu
                    # Pencocokan nama
                    if recognized_name.strip().lower() == username.strip().lower():
                        print("Pengenalan wajah berhasil.")
                        cv.rectangle(img, (x, y), (x + w, y + h), (0, 255, 0), 2)
                        cv.putText(img, recognized_name, (x, y - 10), cv.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
                    else:
                        print("Pengenalan wajah gagal: Nama tidak cocok.")
                else:
                    print("Pengenalan wajah gagal: Kepercayaan rendah.")
            except ValueError:
                print("Wajah dikenali tetapi tidak ada nama yang cocok.")
                cv.putText(img, "Wajah dikenali, tapi nama tidak ada", (10, 30), cv.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)

    cv.imshow('Recognize', img)

    # Cek jika pengguna menekan 'q' untuk keluar
    if cv.waitKey(1) & 0xFF == ord('q'):
        break

# Membersihkan resources setelah loop selesai
camera.release()
cv.destroyAllWindows()
