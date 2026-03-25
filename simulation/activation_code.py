import random
import requests
import string

API_URL = "http://127.0.0.1:8000/drone_api/drones/register"

def generate_serial():
    return "DRONE-" + ''.join(random.choices(string.digits, k=6))

def generate_code():
    return ''.join(random.choices(string.ascii_uppercase + string.digits, k=6))

data = {
    "serial_number": generate_serial(),
    "activation_code": generate_code()
}

response = requests.post(API_URL, json=data)

print(response.json())