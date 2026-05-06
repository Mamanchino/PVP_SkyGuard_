from flask import Flask, Response, request, abort
import airsim
import cv2
import numpy as np
import threading
import time

app = Flask(__name__)

AIRSIM_HOST = "127.0.0.1"
AIRSIM_PORT = 41451
DEFAULT_CAMERA_NAME = "0"

TARGET_FPS = 30
JPEG_QUALITY = 50

streams = {}
streams_lock = threading.Lock()


class CameraStream:
    def __init__ (self, vehicle_name, camera_name):
        self.vehicle_name = vehicle_name or ""
        self.camera_name = camera_name
        self.latest_frame = None
        self.running = True
        self.lock = threading.Lock()


        self.client = airsim.MultirotorClient(ip=AIRSIM_HOST, port=AIRSIM_PORT)
        self.client.confirmConnection()

        self.thread = threading.Thread(target=self._capture_loop, daemon=True)
        self.thread.start()

    def _capture_loop(self):
        frame_interval = 1.0 / TARGET_FPS
        request = airsim.ImageRequest(
            self.camera_name,
            airsim.ImageType.Scene,
            False,
            False
        )

        while self.running:
            started = time.perf_counter()

            try:
                responses = self.client.simGetImages([request], vehicle_name=self.vehicle_name)
                if not responses:
                    time.sleep(0.01)
                    continue
                response = responses[0]
                if response.width == 0 or response.height == 0:
                    time.sleep(0.01)
                    continue

                img = np.frombuffer(response.image_data_uint8, dtype=np.uint8)
                img = img.reshape(response.height, response.width, 3)

                img = cv2.cvtColor(img, cv2.COLOR_RGB2BGR)

                ok, jpeg = cv2.imencode('.jpg', img, [cv2.IMWRITE_JPEG_QUALITY, JPEG_QUALITY])

                if ok:
                    with self.lock:
                        self.latest_frame = jpeg.tobytes()
                
            except Exception as e:
                print(f"AirSim capture error {self.vehicle_name}/{self.camera_name}: {e}")
                time.sleep(0.25)

            elapsed = time.perf_counter() - started
            sleep_time = max(0, frame_interval - elapsed)
            time.sleep(sleep_time)

    def get_frame(self):
        with self.lock:
            return self.latest_frame
        
def get_stream(vehicle_name, camera_name):
    key = (vehicle_name or "", camera_name)
    with streams_lock:
        if key not in streams:
            streams[key] = CameraStream(vehicle_name, camera_name)

        return streams[key]

def generate_frames(vehicle_name, camera_name):
    stream = get_stream(vehicle_name, camera_name)

    while True:
        frame = stream.get_frame()
        if frame is None:
            time.sleep(0.03)
            continue

        yield (
            b"--frame\r\n"
            b"Content-Type: image/jpeg\r\n"
            b"Cache-Control: no-cache\r\n\r\n" +
            frame +
            b"\r\n"
        )
        time.sleep(0.01)

@app.route("/frame")
@app.route("/video_feed")

def video_feed():
    vehicle_name = request.args.get("vehicle_name", "")
    camera_name = request.args.get("camera_name", DEFAULT_CAMERA_NAME)

    if not camera_name:
        abort(400, "camera_name is required")
    return Response(
        generate_frames(vehicle_name, camera_name),
        mimetype="multipart/x-mixed-replace; boundary=frame"
    )

if __name__ == "__main__":
    app.run(
        host="0.0.0.0",
        port=5000,
        debug=False,
        threaded = True
    )
    

        


# # Create ONE global client
# client = airsim.MultirotorClient(ip=AIRSIM_HOST, port=AIRSIM_PORT)
# client.confirmConnection()

# def generate_frames(vehicle_name, camera_name):
#     while True:
#         try:
#             image = client.simGetImage(
#                 camera_name,
#                 airsim.ImageType.Scene,
#                 vehicle_name=vehicle_name
#             )

#             if image:
#                 yield (b'--frame\r\n'
#                        b'Content-Type: image/png\r\n\r\n' + image + b'\r\n')

#             time.sleep(0.03)  # ~30 FPS cap

#         except Exception as e:
#             print("Error:", e)
#             break


# @app.route("/video_feed")
# def video_feed():
#     vehicle_name = request.args.get("vehicle_name")
#     camera_name = request.args.get("camera_name", DEFAULT_CAMERA_NAME)

#     return Response(
#         generate_frames(vehicle_name, camera_name),
#         mimetype='multipart/x-mixed-replace; boundary=frame'
#     )


# if __name__ == "__main__":
#     app.run(host="0.0.0.0", port=5000, debug=False)