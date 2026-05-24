import json
from pathlib import Path
def getUdpPort():
    settings_path = Path(__file__).with_name('settings.json')
    with settings_path.open("r") as file:
        data = json.load(file)

    udp_ports = {}

    for vehicle_name, vehicle_config in data["Vehicles"].items():
        udp_port = vehicle_config.get("UdpPort") + 100
        if udp_port is None:
            continue

        udp_ports[vehicle_name] = "udpin:0.0.0.0:" + str(udp_port)   
    return udp_ports