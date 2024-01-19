#!/bin/bash

echo "Building image"
docker build -t timesheet.568 .

#docker save timesheet.568 -o dockers/timesheet.tar.gz

#docker rm -f timesheet-app