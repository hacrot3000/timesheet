# -*- coding: utf-8 -*-
import os

if os.environ.get('MYSQL_HOST') is not None:
    host=os.environ['MYSQL_HOST']
else:
    host="localhost"

if os.environ.get('MYSQL_HOST') is not None:
    user=os.environ['MYSQL_USER']
else:
    user="duongtc"

if os.environ.get('MYSQL_HOST') is not None:
    password=os.environ['MYSQL_PASS']
else:
    password="y9nhzJQR3"

if os.environ.get('MYSQL_HOST') is not None:
    database=os.environ['MYSQL_DB']
else:
    database="timesheet"

if os.environ.get('MYSQL_HOST') is not None:
    dbport=int(os.environ['MYSQL_PORT'])
else:
    dbport=3306

if os.environ.get('MYSQL_HOST') is not None:
    zk_ip=os.environ['ZK_IP']
else:
    zk_ip='10.20.15.10'

if os.environ.get('MYSQL_HOST') is not None:
    zk_port=int(os.environ['ZK_PORT'])
else:
    zk_port=4370
