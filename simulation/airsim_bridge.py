from flask import Flask, Response, jsonify, request
import numpy as np
import airsim

app = Flask(__name__)
AIRSIM_HOST = "127.0.0.1"
AIRSIM_PORT = 41451
DEFAULT_CAMERA_NAME = "0"

def get_client():
    client = airsim.MultirotorClient(ip=AIRSIM_HOST, port=AIRSIM_PORT)
    client.confirmConnection()
    return client

@app.route("/frame")
def frame():
    vehicle_name = request.args.get("vehicle_name")
    camera_name = request.args.get("camera_name", DEFAULT_CAMERA_NAME)

    try:
        client = get_client()
        image = client.simGetImage(
            camera_name,
            airsim.ImageType.Scene,
            vehicle_name=vehicle_name
        )

        if not image:
            return jsonify({
                "error": "AirSim returned an empty image",
                "vehicle_name": vehicle_name,
            }), 503
        return Response(image, mimetype="image/png")
    except Exception as exc:
        return jsonify({
            "error": "Failed to get AirSim frame",
            "details": str(exc),
            "vechicle_name": vehicle_name,
        }), 500
if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
