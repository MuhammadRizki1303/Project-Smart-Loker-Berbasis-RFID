from flask import Flask, request, jsonify
from flask_cors import CORS  # Tambahkan Flask-CORS untuk menangani CORS
import cv2
import numpy as np
import base64
from deepface import DeepFace

app = Flask(__name__)
CORS(app)  # Izinkan semua permintaan dari browser

def calculate_distance(embedding1, embedding2):
    return np.linalg.norm(np.array(embedding1) - np.array(embedding2))

@app.route('/authenticate', methods=['POST'])
def authenticate():
    try:
        data = request.json
        if not data or 'image1' not in data:
            return jsonify({"status": "gagal", "message": "Data gambar tidak lengkap."}), 400

        # Decode gambar dari Base64
        image1_base64 = data['image1'].split(",")[1]
        image1_data = base64.b64decode(image1_base64)
        image1 = cv2.imdecode(np.frombuffer(image1_data, np.uint8), cv2.IMREAD_COLOR)

        # Gambar referensi (disimpan di server)
        reference_image_path = 'reference_image.jpg'
        reference_image = cv2.imread(reference_image_path)

        # Ekstraksi embedding menggunakan DeepFace
        embedding1 = DeepFace.represent(image1, model_name="VGG-Face", enforce_detection=False)
        embedding2 = DeepFace.represent(reference_image, model_name="VGG-Face", enforce_detection=False)

        # Hitung jarak antara embedding
        distance = calculate_distance(embedding1[0]['embedding'], embedding2[0]['embedding'])
        print(f"Jarak embedding: {distance}")

        # Threshold autentikasi
        if distance < 1.0:
            return jsonify({"status": "sukses", "message": "Autentikasi berhasil."})
        else:
            return jsonify({"status": "gagal", "message": "Wajah tidak cocok."})
    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({"status": "gagal", "message": f"Kesalahan: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
