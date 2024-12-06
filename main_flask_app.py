from flask import Flask, request, jsonify
from flask_cors import CORS
import cv2
import numpy as np
import base64
from deepface import DeepFace
import logging

# Konfigurasi logging
logging.basicConfig(level=logging.INFO)

app = Flask(__name__)
CORS(app)  # Mengizinkan semua permintaan lintas domain

# Fungsi untuk menghitung jarak Euclidean antara dua embedding
def calculate_distance(embedding1, embedding2):
    return np.linalg.norm(np.array(embedding1) - np.array(embedding2))

# Endpoint untuk autentikasi wajah
@app.route('/authenticate', methods=['POST'])
def authenticate():
    try:
        # Ambil data JSON dari permintaan
        data = request.json
        if not data or 'image1' not in data:
            return jsonify({"status": "gagal", "message": "Data gambar tidak lengkap."}), 400

        # Decode gambar dari Base64
        try:
            image1_base64 = data['image1'].split(",")[1]
            image1_data = base64.b64decode(image1_base64)
        except Exception as e:
            logging.error(f"Kesalahan decoding Base64: {str(e)}")
            return jsonify({"status": "gagal", "message": "Format Base64 tidak valid."}), 400

        # Proses gambar dari buffer
        image1 = cv2.imdecode(np.frombuffer(image1_data, np.uint8), cv2.IMREAD_COLOR)
        if image1 is None:
            return jsonify({"status": "gagal", "message": "Gambar tidak valid."}), 400

        # Membaca gambar referensi dari server
        reference_image_path = 'reference_image.jpg'
        reference_image = cv2.imread(reference_image_path)
        if reference_image is None:
            logging.error("Gambar referensi tidak ditemukan.")
            return jsonify({"status": "gagal", "message": "Gambar referensi tidak ditemukan di server."}), 500

        # Ekstraksi embedding dari kedua gambar menggunakan DeepFace
        embedding1 = DeepFace.represent(image1, model_name="VGG-Face", enforce_detection=False)
        embedding2 = DeepFace.represent(reference_image, model_name="VGG-Face", enforce_detection=False)

        # Hitung jarak embedding
        distance = calculate_distance(embedding1[0]['embedding'], embedding2[0]['embedding'])
        logging.info(f"Jarak embedding: {distance}")

        threshold = 1
        # Membandingkan jarak dengan threshold untuk autentikasi
        if distance < threshold:
            return jsonify({"status": "sukses", "message": "Autentikasi berhasil."})
        else:
            return jsonify({"status": "gagal", "message": "Wajah tidak cocok."})
    except Exception as e:
        # Menangani kesalahan dan mengembalikan pesan error
        logging.error(f"Kesalahan: {str(e)}")
        return jsonify({"status": "gagal", "message": f"Kesalahan: {str(e)}"}), 500

# Menjalankan server Flask
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
