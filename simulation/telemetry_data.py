from pymavlink import mavutil
import threading

class BatteryListener:
    def __init__ (self, connection_string="udpin:172.28.144.1:14580"):
        self.connection_string=connection_string
        self.battery_level=None
        self.running=True
        self.thread=threading.Thread(target=self._loop, daemon=True)
        self.thread.start()
    
    def _loop(self):
        master=mavutil.mavlink_connection(self.connection_string)

        while self.running:
            msg=master.recv_match(
                type=["BATTERY_STATUS", "SYS_STATUS"],
                blocking=True,
                timeout=1
            )

            if not msg:
                continue

            if msg.get_type() == "BATTERY_STATUS":
                level=msg.battery_remaining
                if level != -1:
                    self.battery_level = level
            elif msg.get_type() == "SYS_STATUS":
                level=msg.battery_remaining
                if level != -1:
                    self.battery_level = level
    def get_battery(self):
        return self.battery_level