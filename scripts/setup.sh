#!/bin/bash

if [[ $1 = "" ]]
then
	echo "no build number passed"
	exit 1
fi

BUILD=$1

ln -nfs ~/user_media ~/build/${BUILD}/user_media
