from flask import Flask, Response
import cv2
from ultralytics import YOLO
import threading
import time
from dotenv import load_dotenv
import os

# pip install python-dotenv ultralytics opencv-python flask

load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '..', '.env'))

PI_STREAM_URL = os.getenv("PI_STREAM_URL")

app = Flask(__name__)
model = YOLO("yolov8n.pt")

latest_frame = None
lock = threading.Lock()

def capture_frames():
    global latest_frame
    cap = cv2.VideoCapture(PI_STREAM_URL)
    cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)

    while True:
        ok, frame = cap.read()
        if not ok:
            time.sleep(0.2)
            cap.release()
            cap = cv2.VideoCapture(PI_STREAM_URL)
            continue

        with lock:
            latest_frame = frame.copy()

def generate_annotated():
    while True:
        with lock:
            if latest_frame is None:
                frame = None
            else:
                frame = latest_frame.copy()

        if frame is None:
            time.sleep(0.05)
            continue

        results = model(frame, classes=[0], verbose=False)  # class 0 = person
        annotated = results[0].plot()

        ok, buffer = cv2.imencode('.jpg', annotated)
        if not ok:
            continue

        jpg = buffer.tobytes()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + jpg + b'\r\n')

@app.route('/')
def index():
    return '<html><body style="margin:0;background:#111;"><img src="/video_feed" style="width:100%;height:auto;"></body></html>'

@app.route('/video_feed')
def video_feed():
    return Response(generate_annotated(),
                    mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    t = threading.Thread(target=capture_frames, daemon=True)
    t.start()
    app.run(host='0.0.0.0', port=5050, threaded=True)