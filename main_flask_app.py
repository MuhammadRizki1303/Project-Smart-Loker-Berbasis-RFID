from flask import Flask, request, jsonify
import numpy as np
import cv2
import base64
from deepface import DeepFace
import os

app = Flask(__name__)

# Membuat direktori untuk menyimpan hasil crop
os.makedirs("cropped_faces", exist_ok=True)

def calculate_distance(embedding1, embedding2):
    embedding1_vector = np.array(embedding1[0]['embedding'])
    embedding2_vector = np.array(embedding2[0]['embedding'])
    return np.linalg.norm(embedding1_vector - embedding2_vector)

def save_cropped_face(image, filename):
    try:
        faces = DeepFace.extract_faces(image, detector_backend="mtcnn", enforce_detection=True)
        if faces:
            detected_face = faces[0]["face"]
            face_image = (detected_face * 255).astype(np.uint8)
            face_image_bgr = cv2.cvtColor(face_image, cv2.COLOR_RGB2BGR)
            cv2.imwrite(f"cropped_faces/{filename}.jpg", face_image_bgr)
        else:
            print(f"No face detected for {filename}")
    except Exception as e:
        print(f"Error cropping face for {filename}: {e}")

@app.route('/authenticate', methods=['POST'])
def authenticate():
    try:
        data = request.json
        if not data:
            print("Error: Tidak ada data JSON yang diterima.")
            return jsonify({"error": "Tidak ada data yang dikirim"}), 400

        image1_base64 = data['image1']
        image2_base64 = data['image2']

        # Decode base64 images
        image1_data = base64.b64decode(image1_base64)
        image2_data = base64.b64decode(image2_base64)
        image1 = cv2.imdecode(np.frombuffer(image1_data, np.uint8), cv2.IMREAD_COLOR)
        image2 = cv2.imdecode(np.frombuffer(image2_data, np.uint8), cv2.IMREAD_COLOR)

        # Simpan hasil cropping wajah
        save_cropped_face(image1, "cropped_image1")
        save_cropped_face(image2, "cropped_image2")

        # Deteksi wajah dan ekstraksi embedding dengan DeepFace
        embedding1 = DeepFace.represent(image1, model_name="VGG-Face")
        embedding2 = DeepFace.represent(image2, model_name="VGG-Face")

        # Hitung jarak antara kedua embedding
        distance = calculate_distance(embedding1, embedding2)
        print("Distance:", distance)
        
        # Threshold untuk autentikasi
        threshold = 1.0
        if distance < threshold:
            return jsonify({"status": "sukses", "message": "Autentikasi berhasil"})
        else:
            return jsonify({"status": "gagal", "message": "Autentikasi gagal"})
    except Exception as e:
        print(f"Error during authentication: {e}")
        return jsonify({"error": "Terjadi kesalahan saat autentikasi"}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)