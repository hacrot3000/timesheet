# -*- coding: utf-8 -*-
import os
import sys
import mysql.connector
from datetime import datetime
import config

CWD = os.path.dirname(os.path.realpath(__file__))
ROOT_DIR = os.path.dirname(CWD)
sys.path.append(ROOT_DIR)

#pip install -U pyzk
from zk import ZK, const

mydb = mysql.connector.connect(
  host=config.host,
  user=config.user,
  password=config.password,
  database=config.database
)
mycursor = mydb.cursor()

paid_leave_left_this_year = 0


sql = "INSERT IGNORE INTO users (id, username, fullname, is_admin, paid_leave_per_year, paid_leave_left_this_year) VALUES (%s, %s, %s, %s, 12, " + str(paid_leave_left_this_year) + ")"
#sql = "INSERT IGNORE INTO users (id, username, fullname, is_admin, paid_leave_per_year, paid_leave_left_this_year) VALUES (%s, %s, %s, %s, 12, " + str(paid_leave_left_this_year) + ") ON DUPLICATE KEY UPDATE email=%s"


conn = None
zk = ZK(config.ip, port=config.port)
try:
    conn = zk.connect()
    print ('Disabling device ...')
    conn.disable_device()
    print ('--- Get User ---')
    users = conn.get_users()
    for user in users:
        #privilege = 'User'
        #if user.privilege == const.USER_ADMIN:
        #    privilege = 'Admin'
        #print ('+ UID #{}'.format(user.uid))
        #print ('  Name       : {}'.format(user.name))
        #print ('  Privilege  : {}'.format(privilege))
        #print ('  Password   : {}'.format(user.password))
        #print ('  Group ID   : {}'.format(user.group_id))
        #print ('  User  ID   : {}'.format(user.user_id))

        is_admin = 0
        if user.privilege == const.USER_ADMIN:
            is_admin = 1

        x = user.name.split(" ")

        username = x.pop()

        for n in x:
            username = username + n[0]
        email = username.lower() + "@568e.vn"
        username = username.lower() + str(user.uid)

        val = (user.user_id, username, user.name, is_admin)
        mycursor.execute(sql, val)


    #print ('--- Get Attendances ---')
    #attendances = conn.get_attendance()
    #for attendance in attendances:
    #    print(attendance)

    print ('Enabling device ...')
    conn.enable_device()
except Exception as e:
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()

mydb.commit()
