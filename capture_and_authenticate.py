import cv2
import base64
import requests
import numpy as np
import webbrowser

# URL of the Flask API for authentication
AUTH_API_URL = "http://127.0.0.1:5000/authenticate"

def capture_image():
    cap = cv2.VideoCapture(0)
    if not cap.isOpened():
        print("Error: Kamera tidak bisa dibuka.")
        return None

    while True:
        ret, frame = cap.read()
        if not ret:
            print("Error: tidak bisa mengambil gambar.")
            break
        
        # Tampilkan kamera
        cv2.imshow("Tekan Spasi untuk Mengambil Gambar", frame)
        
        # Tekan spasi untuk mengambil gambar
        key = cv2.waitKey(1) & 0xFF
        if key == ord(' '):  # Spasi ditekan
            cv2.imwrite("captured_image.jpg", frame)
            print("Gambar diambil dan disimpan dengan nama 'captured_image.jpg'")
            break
        elif key == ord('q'):  # Tekan 'q' untuk keluar tanpa mengambil gambar
            print("Proses dibatalkan.")
            frame = None
            break

    cap.release()
    cv2.destroyAllWindows()
    
    return frame

def image_to_base64(image):
    _, buffer = cv2.imencode('.jpg', image)
    image_base64 = base64.b64encode(buffer).decode('utf-8')
    return image_base64

def authenticate_image(captured_image):
    captured_image_base64 = image_to_base64(captured_image)
    
    try:
        with open("reference_image.jpg", "rb") as ref_file:
            reference_image_base64 = base64.b64encode(ref_file.read()).decode('utf-8')
    except FileNotFoundError:
        print("Error: referensi wajah tidak ditemukan.")
        return

    data = {
        "image1": captured_image_base64,
        "image2": reference_image_base64
    }
    
    response = requests.post(AUTH_API_URL, json=data)
    
    if response.status_code == 200:
        try:
            result = response.json()
            if result["status"] == "sukses":
                print("Login Sukses sobad!")
                open_dashboard()
            else:
                print("Login gagal. Wajah tidak cocok.")
        except ValueError:
            print("Error: Tidak dapat parsing JSON. Isi respons:", response.text)
    else:
        print("Error saat otentikasi:", response.status_code, response.text)

def open_dashboard():
    chrome_path = "C:/Users/Rizki/AppData/Local/Google/Chrome/Application/chrome.exe %s"
    webbrowser.get(chrome_path).open("http://localhost/Project-Smart-Loker-Berbasis-RFID/input.php")

if __name__ == "__main__":
    print("Tolong posisikan muka anda didepan kamera")
    captured_image = capture_image()
    
    if captured_image is not None:
        authenticate_image(captured_image)
