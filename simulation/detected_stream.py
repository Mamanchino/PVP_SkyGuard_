from flask import Flask, Response
import cv2
from ultralytics import YOLO
import threading
import time
import requests
from dotenv import load_dotenv
import os


# pip install python-dotenv ultralytics opencv-python flask requests

load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '..', '.env'))

PI_STREAM_URL     = os.getenv("PI_STREAM_URL")
LARAVEL_BASE_URL  = os.getenv("LARAVEL_BASE_URL", "http://localhost:8000")
DETECTION_STREAM_URL = os.getenv("DETECTION_STREAM_URL")

# Minimum seconds between notifications
NOTIFICATION_COOLDOWN = 15.0

app   = Flask(__name__)
model = YOLO("yolov8n.pt")

latest_frame     = None
lock             = threading.Lock()
last_notified_at = 0.0

def resolve_drone_id() -> int:
    try:
        r = requests.get(
            f"{LARAVEL_BASE_URL}/drones/by-stream-url",
            params={"url": DETECTION_STREAM_URL},
            timeout=5,
        )
        r.raise_for_status()
        drone_id = r.json()["drone_id"]
        print(f"[init] Resolved drone_id={drone_id} for {DETECTION_STREAM_URL}")
        return drone_id
    except Exception as e:
        raise RuntimeError(f"Could not resolve drone ID from stream URL: {e}")

DRONE_ID = resolve_drone_id()

def post_notification(confidence: float):
    """Fire-and-forget POST to Laravel /detections."""
    url     = f"{LARAVEL_BASE_URL}/detections"
    payload = {
        "type":       "person_detected",
        "message":    "Person detected by drone camera",
        "confidence": round(float(confidence), 4),
        "drone_id":   DRONE_ID,
    }
    headers = {
        "Content-Type":       "application/json",
        "Accept":             "application/json",
    }
    try:
        r = requests.post(url, json=payload, headers=headers, timeout=3)
        r.raise_for_status()
        print(f"[notify] POSTed detection (conf={confidence:.2f}) → {r.status_code}")
    except requests.RequestException as e:
        print(f"[notify] Failed to POST detection: {e}")


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
    global last_notified_at

    while True:
        with lock:
            frame = latest_frame.copy() if latest_frame is not None else None

        if frame is None:
            time.sleep(0.05)
            continue

        results   = model(frame, classes=[0], conf=0.6, verbose=False)   # class 0 = person
        annotated = results[0].plot()

        # Notification logic
        boxes = results[0].boxes
        if boxes is not None and len(boxes) > 0:
            now = time.time()
            if now - last_notified_at >= NOTIFICATION_COOLDOWN:
                last_notified_at = now
                best_conf = float(boxes.conf.max())
                t = threading.Thread(
                    target=post_notification,
                    args=(best_conf,),
                    daemon=True,
                )
                t.start()

        ok, buffer = cv2.imencode('.jpg', annotated)
        if not ok:
            continue

        jpg = buffer.tobytes()
        yield (
            b'--frame\r\n'
            b'Content-Type: image/jpeg\r\n\r\n' + jpg + b'\r\n'
        )


@app.route('/')
def index():
    return (
        '<html><body style="margin:0;background:#111;">'
        '<img src="/video_feed" style="width:100%;height:auto;">'
        '</body></html>'
    )


@app.route('/video_feed')
def video_feed():
    return Response(
        generate_annotated(),
        mimetype='multipart/x-mixed-replace; boundary=frame',
    )


if __name__ == '__main__':
    t = threading.Thread(target=capture_frames, daemon=True)
    t.start()
    app.run(host='0.0.0.0', port=5050, threaded=True)