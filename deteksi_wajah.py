import cv2

cascade_wajah = cv2.CascadeClassifier("haarcascade_frontalface_default.xml")

cam = cv2.VideoCapture(0)
while True:
    _, frame = cam.read()
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY) # berfungsi untuk membuat camera menjadi hitam putih supaya pendeteksi wajahnya lebih jelas#
    faces = cascade_wajah.detectMultiScale(gray, 1.3, 3) # setelah 
    for x, y, w, h in faces:
        cv2.rectangle(frame, (x,y), (x+w, y+h), (0, 255, 0), 3)

    cv2.imshow("Deteksi Wajah", frame)

    if cv2.waitKey(1) & 0xff == ord('x') :
        break

cam.release()
cv2.destroyAllWindows()
