#!/bin/bash
# gets and echos the current build number
#svn info
build=`git rev-parse HEAD`
echo "${build:0:6}${build:(-6)}"
