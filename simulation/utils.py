import json

def getUdpPort():
    with open("./simulation/settings.json", "r") as file:
        data = json.load(file)

    forwared_ports = {
        "Drone1": 14680,
        "Drone4": 14683,
        "Drone5": 14684
    }

    udp_port = {}

    for vehicle_name in data["Vehicles"]:
        udp_port[vehicle_name] = "udpin:0.0.0.0:" + str(forwared_ports[vehicle_name])
    return udp_port