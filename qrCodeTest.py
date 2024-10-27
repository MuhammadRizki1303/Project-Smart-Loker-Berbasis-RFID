import qrcode

# Membuat instance QRCode
qr = qrcode.QRCode(
    version=1,
    box_size=10,
    border=5
)

# Menyimpan link ke variabel data
data = "https://muhammadrizki1303.github.io/Web_penggunaan_loker_RFID/"
qr.add_data(data)
qr.make(fit=True)

# Membuat dan menyimpan gambar QR code
img = qr.make_image(fill="black", back_color="white")
img.save("QRcode.png")
