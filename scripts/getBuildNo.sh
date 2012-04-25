#!/bin/bash
# gets and echos the current build number
#svn info
build=`svn info | grep "Revision" | awk '{print $2}'`
echo $build
