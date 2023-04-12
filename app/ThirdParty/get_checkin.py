# -*- coding: utf-8 -*-
import os
import sys
import mysql.connector

CWD = os.path.dirname(os.path.realpath(__file__))
ROOT_DIR = os.path.dirname(CWD)
sys.path.append(ROOT_DIR)

from zk import ZK, const

mydb = mysql.connector.connect(
  host="10.9.1.9",
  user="duongtc",
  password="y9nhzJQR3*",
  database="timesheet"
)
mycursor = mydb.cursor()

sql = "insert ignore into checkin (user_id, `date`, `time`) values (%s, %s, %s)"


conn = None
zk = ZK('192.168.1.14', port=4370)
try:
    conn = zk.connect()
    #print ('Disabling device ...')
    #conn.disable_device()

    print ('--- Get Attendances ---')
    attendances = conn.get_attendance()
    for attendance in attendances:
        val = (attendance.user_id, attendance.timestamp, attendance.timestamp)
        mycursor.execute(sql, val)

    #print ('Enabling device ...')
    #conn.enable_device()
except Exception as e:
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()

mydb.commit()