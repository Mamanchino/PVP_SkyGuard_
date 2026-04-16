import random
#import requests
import mysql.connector
import uuid
import string

API_URL = "http://127.0.0.1:8000/api/drones/register"

# Database connection
db = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="pvp_skyguard"
)
mycursor = db.cursor()
for i in range(20):
    serial_number = f"DRONE-{uuid.uuid4().hex[:6].upper()}"
    activation_code = ''.join(random.choices(string.ascii_uppercase + string.digits, k=8))
    model = random.choice(["DJI Mini 5 Pro", "DJI Mavic 4 Pro", "DJI Neo 2"])
    name = "My Drone"
    status = "offline"
    sql = "INSERT INTO drones (name, serial_number, activation_code, model, status) VALUES (%s, %s, %s, %s, %s)"
    val = (name, serial_number, activation_code, model, status)
    mycursor.execute(sql, val)
db.commit()

# def generate_serial():
#     return "DRONE-" + ''.join(random.choices(string.digits, k=6))

# def generate_code():
#     return ''.join(random.choices(string.ascii_uppercase + string.digits, k=6))

# data = {
#     "serial_number": generate_serial(),
#     "activation_code": generate_code(),
#     "model": random.choice(["DJI Mini 5 Pro", "DJI Mavic 4 Pro", "DJI Neo 2"])
# }

# response = requests.post(API_URL, json=data)

# print(response.json())