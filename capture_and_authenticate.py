import cv2
import base64
import requests
import numpy as np
import webbrowser  # Import the webbrowser module to open HTML pages

# URL of the Flask API for authentication
AUTH_API_URL = "http://127.0.0.1:5000/authenticate"

# Capture an image from the camera
def capture_image():
    cap = cv2.VideoCapture(0)  # Open the default camera (0)
    if not cap.isOpened():
        print("Error: Kamera tidak bisa dibuka.")
        return None

    ret, frame = cap.read()
    cap.release()
    
    if not ret:
        print("Error: tidak bisa mengambil gambar.")
        return None

    # Save the captured image for verification
    cv2.imwrite("captured_image.jpg", frame)
    print("Gambar diambil dan disimpan dengan nama 'captured_image.jpg'")
    
    return frame

# Convert image to base64
def image_to_base64(image):
    _, buffer = cv2.imencode('.jpg', image)
    image_base64 = base64.b64encode(buffer).decode('utf-8')
    return image_base64

# Send the captured image for authentication
def authenticate_image(captured_image):
    # Encode the captured image in base64
    captured_image_base64 = image_to_base64(captured_image)
    
    # Load the reference image (pre-captured image)
    try:
        with open("reference_image.jpg", "rb") as ref_file:
            reference_image_base64 = base64.b64encode(ref_file.read()).decode('utf-8')
    except FileNotFoundError:
        print("Error: referensi wajah tidak ditemukan.")
        return

    # Send the images to the Flask authentication API
    data = {
        "image1": captured_image_base64,  # Captured image
        "image2": reference_image_base64   # Stored reference image
    }
    
    response = requests.post(AUTH_API_URL, json=data)
    
    if response.status_code == 200:
        result = response.json()
        if result["status"] == "sukses":
            print("Login Sukses sobad!")
            open_dashboard()  # Open the dashboard if login is successful
        else:
            print("login gagal. wajah tidak ditemukan.")
    else:
        print("Error saat otentikasi:", response.json().get("error", "Unknown error"))

# Open the dashboard page if authenticated
def open_dashboard():
    chrome_path = "C:/Users/Rizki/AppData/Local/Google/Chrome/Application/chrome.exe %s"  # Sesuaikan path Chrome di komputer Anda
    webbrowser.get(chrome_path).open("http://localhost/Project-Smart-Loker-Berbasis-RFID/input.php")

if __name__ == "__main__":
    print("Tolong posisikan muka anda didepan kamera")
    captured_image = capture_image()
    
    if captured_image is not None:
        authenticate_image(captured_image)
