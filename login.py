from flask import Flask, render_template, redirect, url_for
import cv2
import face_recognition
import numpy as np
import os

app = Flask(__name__)

# Muat semua gambar admin dari folder
admin_images = []
admin_names = []
folder_path = "admin_images/"

for file in os.listdir(folder_path):
    if file.endswith(".jpg") or file.endswith(".png"):
        image = face_recognition.load_image_file(os.path.join(folder_path, file))
        encoding = face_recognition.face_encodings(image)[1]
        admin_images.append(encoding)
        admin_names.append(os.path.splitext(file)[0])  # Nama admin dari nama file

@app.route('/')
def index():
    return render_template('login.php')

@app.route('/login', methods=['GET'])
def login_face():
    video_capture = cv2.VideoCapture(0)
    while True:
        ret, frame = video_capture.read()
        rgb_frame = frame[:, :, ::-1]
        face_locations = face_recognition.face_locations(rgb_frame)
        face_encodings = face_recognition.face_encodings(rgb_frame, face_locations)

        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(admin_images, face_encoding)
            face_distances = face_recognition.face_distance(admin_images, face_encoding)
            best_match_index = np.argmin(face_distances)

            if matches[best_match_index]:
                name = admin_names[best_match_index]
                print(f"Login sukses untuk admin {name}!")
                video_capture.release()
                cv2.destroyAllWindows()
                return redirect(url_for('input', name=name))

        cv2.imshow('Video', frame)

        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

    video_capture.release()
    cv2.destroyAllWindows()
    return "Wajah tidak dikenali!"

@app.route('/dashboard/<name>')
def dashboard(name):
    return f"Selamat datang, {name}! Ini adalah halaman dashboard admin."

if __name__ == '__main__':
    app.run(debug=True)
