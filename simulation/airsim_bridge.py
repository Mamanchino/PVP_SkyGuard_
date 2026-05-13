import airsim
import cv2
import numpy as np
import threading
import time
from flask import Flask, Response
from ultralytics import YOLO
from telemetry_data import BatteryListener
from flask_cors import CORS

app = Flask(__name__)
CORS(app)
# ---------------- CONFIG ----------------
AIRSIM_HOST = "127.0.0.1"
AIRSIM_PORT = 41451

CAMERA_NAME = "0"

TARGET_FPS = 15
JPEG_QUALITY = 70

# ---------------- YOLO MODEL ----------------
model = YOLO("yolov8n.pt")

latest_frame = None
frame_lock = threading.Lock()
battery_listener = BatteryListener()

# ---------------- AIRSIM + YOLO LOOP ----------------
def camera_loop():
    global latest_frame

    client = airsim.MultirotorClient(
        ip=AIRSIM_HOST,
        port=AIRSIM_PORT
    )

    client.confirmConnection()
    print("Connected to AirSim")

    image_request = airsim.ImageRequest(
        CAMERA_NAME,
        airsim.ImageType.Scene,
        False,
        False
    )

    frame_interval = 1.0 / TARGET_FPS

    while True:
        start = time.perf_counter()

        try:
            responses = client.simGetImages(
                [image_request]
            )

            if not responses:
                continue

            response = responses[0]

            if response.width == 0 or response.height == 0:
                continue

            # AirSim -> numpy
            img = np.frombuffer(
                response.image_data_uint8,
                dtype=np.uint8
            )

            img = img.reshape(
                response.height,
                response.width,
                3
            )

            frame = cv2.cvtColor(
                img,
                cv2.COLOR_RGB2BGR
            )

            # ---------------- YOLO DETECTION ----------------
            # Higher imgsz + lower confidence
            # = better far-distance human detection
            results = model(
                frame,
                imgsz=640,
                verbose=False
            )

            for r in results:
                for box in r.boxes:

                    cls = int(box.cls[0])

                    # person class = 0
                    if cls != 0:
                        continue

                    conf = float(box.conf[0])

                    # Lower threshold for far humans
                    if conf < 0.20:
                        continue

                    x1, y1, x2, y2 = map(
                        int,
                        box.xyxy[0]
                    )

                    # Draw bounding box
                    cv2.rectangle(
                        frame,
                        (x1, y1),
                        (x2, y2),
                        (0, 255, 0),
                        2
                    )

                    label = f"Person {conf:.2f}"

                    cv2.putText(
                        frame,
                        label,
                        (x1, y1 - 10),
                        cv2.FONT_HERSHEY_SIMPLEX,
                        0.7,
                        (0, 255, 0),
                        2
                    )

            # JPEG encode
            ok, jpeg = cv2.imencode(
                ".jpg",
                frame,
                [
                    cv2.IMWRITE_JPEG_QUALITY,
                    JPEG_QUALITY
                ]
            )

            if ok:
                with frame_lock:
                    latest_frame = jpeg.tobytes()

        except Exception as e:
            print("AirSim error:", e)
            time.sleep(0.1)

        elapsed = time.perf_counter() - start

        time.sleep(
            max(0, frame_interval - elapsed)
        )


# ---------------- VIDEO STREAM ----------------
def generate_frames():
    while True:

        with frame_lock:
            frame = latest_frame

        if frame is None:
            blank = np.zeros(
                (480, 640, 3),
                dtype=np.uint8
            )

            _, jpeg = cv2.imencode(
                ".jpg",
                blank
            )

            frame = jpeg.tobytes()

        yield (
            b"--frame\r\n"
            b"Content-Type: image/jpeg\r\n\r\n"
            + frame +
            b"\r\n"
        )


# ---------------- ROUTES ----------------
@app.route("/video_feed")
@app.route("/frame")
def video_feed():
    return Response(
        generate_frames(),
        mimetype="multipart/x-mixed-replace; boundary=frame"
    )
@app.route("/battery")
def battery():
    return {"battery": battery_listener.get_battery()}


# ---------------- MAIN ----------------
if __name__ == "__main__":

    # Start AirSim + YOLO thread
    threading.Thread(
        target=camera_loop,
        daemon=True
    ).start()

    # Start Flask server
    app.run(
        host="0.0.0.0",
        port=5000,
        debug=False,
        threaded=True
    )