#!/bin/bash
while [ true ]
do
	curl http://localhost/soulmate/autoproc/process
	sleep 30
done
