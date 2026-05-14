from pymavlink import mavutil
import threading
import time

ONLINE_THRESHOLD = 5  # seconds

class TelemetryListener:
    def __init__ (self, connection_string):
        self.connection_string=connection_string
        self.battery_level=None
        self.connection_status=None
        self.last_connection_time=None
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
                elif msg_type == "SYS_STATUS":
                    level=msg.battery_remaining
                    if level != -1:
                        self.battery_level = level
            self.connection_status = (self.last_connection_time is not None and time.time() - self.last_connection_time < ONLINE_THRESHOLD)
            # print(f"Connection status for {self.connection_string}: {'Online' if self.connection_status else 'Offline'}")
            # if msg.get_type() == "BATTERY_STATUS":
            #     print("BATTERY_STATUS battery_remaining:", msg.battery_remaining)

            # if msg.get_type() == "SYS_STATUS":
            #     print("SYS_STATUS battery_remaining:", msg.battery_remaining)


    def get_battery(self):
        return self.battery_level
    def get_connection_status(self):
        return self.connection_status