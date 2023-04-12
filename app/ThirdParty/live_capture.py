# -*- coding: utf-8 -*-
import os
import sys
import mysql.connector
import time

CWD = os.path.dirname(os.path.realpath(__file__))
ROOT_DIR = os.path.dirname(CWD)
sys.path.append(ROOT_DIR)

#pip install -U pyzk
from zk import ZK

i = 0
while i < 10:

    conn = None
    zk = ZK('<IP>', port=4370)
    try:
        conn = zk.connect()
        print("Services started...")

        f = open("/var/log/timesheet_capture", "a")
        f.write("Services started...\n")
        f.close()


        for attendance in conn.live_capture():
            if attendance is None:
                pass
            else:
                print (attendance)

                mydb = mysql.connector.connect(
                  host="10.9.1.9",
                  user="duongtc",
                  password="y9nhzJQR3*",
                  database="timesheet"
                )
                mycursor = mydb.cursor()

                sql = "insert ignore into checkin (user_id, `date`, `time`) values (%s, %s, %s)"
                val = (attendance.user_id, attendance.timestamp, attendance.timestamp)
                mycursor.execute(sql, val)
                mydb.commit()

                print(mycursor.rowcount, "record inserted.")

                f = open("/var/log/timesheet_capture", "a")
                f.write("record inserted" + str(attendance.user_id) + "\n")
                f.close()


    except Exception as e:
        print ("Process terminate : {}".format(e))

        f = open("/var/log/timesheet_capture", "a")
        f.write("Process terminate\n")
        f.close()
        
    finally:
        if conn:
            conn.disconnect()

    time.sleep(3)
