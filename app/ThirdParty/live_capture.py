# -*- coding: utf-8 -*-
import os
import sys
import mysql.connector
import time
from datetime import datetime
import config


CWD = os.path.dirname(os.path.realpath(__file__))
ROOT_DIR = os.path.dirname(CWD)
sys.path.append(ROOT_DIR)

#pip install -U pyzk
from zk import ZK

now = datetime.now()

i = 0
while i < 10:

    conn = None
    zk = ZK(config.zk_ip, port=config.zk_port)
    try:
        conn = zk.connect()
        print(now.strftime("%d/%m/%Y %H:%M:%S"), "Services started...")

        f = open("/var/log/timesheet_capture", "a")
        f.write(now.strftime("%d/%m/%Y %H:%M:%S") + " Services started...\n")
        f.close()


        for attendance in conn.live_capture():
            if attendance is None:
                pass
            else:
                print (attendance)

                mydb = mysql.connector.connect(
                  host=config.host,
                  user=config.user,
                  password=config.password,
                  database=config.database,
                  port=config.dbport
                )
                mycursor = mydb.cursor()

                sql = "insert ignore into checkin (user_id, `date`, `time`) values (%s, %s, %s)"
                val = (attendance.user_id, attendance.timestamp, attendance.timestamp)
                mycursor.execute(sql, val)
                mydb.commit()

                now = datetime.now()

                print(now.strftime("%d/%m/%Y %H:%M:%S"), mycursor.rowcount, " record inserted.")

                f = open("/var/log/timesheet_capture", "a")
                f.write(now.strftime("%d/%m/%Y %H:%M:%S") + " record inserted" + str(attendance.user_id) + "\n")
                f.close()


    except Exception as e:
        print (now.strftime("%d/%m/%Y %H:%M:%S"), "Process terminate : {}".format(e))

        f = open("/var/log/timesheet_capture", "a")
        f.write(now.strftime("%d/%m/%Y %H:%M:%S") + " Process terminate\n")
        f.close()

    finally:
        if conn:
            conn.disconnect()

    time.sleep(3)
