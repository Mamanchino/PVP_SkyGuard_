from pymavlink import mavutil
import threading
import time
import requests

ONLINE_THRESHOLD = 5  # seconds

class TelemetryListener:
    def __init__ (self, drone_name, connection_string):
        self.drone_name = drone_name
        self.connection_string=connection_string
        self.battery_level=None
        self.connection_status=None
        self.last_connection_time=None
        self.low_battery_sent = False
        self.running=True
        self.thread=threading.Thread(target=self._loop, daemon=True)
        self.thread.start()
    
    def _loop(self):
        master=mavutil.mavlink_connection(self.connection_string)
        while self.running:
            msg=master.recv_match(
                type=["BATTERY_STATUS", "SYS_STATUS", "HEARTBEAT"],
                blocking=True,
                timeout=1
            )
            # print("Heartbeat received from system (system %u component %u)" % (master.target_system, master.target_component))
            if msg:
                msg_type=msg.get_type()
                if msg_type=="HEARTBEAT":
                    is_px4 = (
                        msg.type == mavutil.mavlink.MAV_TYPE_QUADROTOR and
                        msg.autopilot == mavutil.mavlink.MAV_AUTOPILOT_PX4
                    )
                    if is_px4:  
                        self.last_connection_time=time.time()
                     
                if msg_type == "BATTERY_STATUS":
                    level=msg.battery_remaining
                    if level != -1:
                        self.battery_level = level
                        if level < 60 and not self.low_battery_sent:
                            self.send_event(self.drone_name, "Low battery level", severity="critical")
                            self.low_battery_sent = True
                        elif level >= 60:
                            self.low_battery_sent = False 
                elif msg_type == "SYS_STATUS":
                    level=msg.battery_remaining
                    if level != -1:
                        self.battery_level = level
            connection_time = time.time()
            self.connection_status = (self.last_connection_time is not None and connection_time < ONLINE_THRESHOLD)
            if connection_time >= 5:
                self.send_event(self.drone_name, "Connection was lost with the drone", severity='error')
            # print(f"Connection status for {self.connection_string}: {'Online' if self.connection_status else 'Offline'}")
            # if msg.get_type() == "BATTERY_STATUS":
            #     print("BATTERY_STATUS battery_remaining:", msg.battery_remaining)

            # if msg.get_type() == "SYS_STATUS":
            #     print("SYS_STATUS battery_remaining:", msg.battery_remaining)


    def get_battery(self):
        return self.battery_level
    def get_connection_status(self):
        return self.connection_status
    
    def send_event(self, drone_name, event_type, severity="info"):
        try:
            response = requests.post(
                "http://127.0.0.1:8000/api/drone-events",
                json={
                    "drone_name": drone_name,
                    "event_type": event_type,
                    "severity": severity,
                },
                timeout=2
            )
            response.raise_for_status()
            print("Event.sent", response.json())
        except requests.RequestException as e:
            print("Failed to send event: ", e)
