#!/bin/sh

# We're going to install all the dependencies for our new data
# integrator machine. This is the provisioning script for the vm.

# http://xkcd.com/1654/

# First update installed packages to newest versions
echo "!!!!!!!!!!!!!!!!!!"
echo "updating packages"
echo "!!!!!!!!!!!!!!!!!!"
sudo apt-get update
echo "!!!!!!!!!!!!!!!!!!"
echo "upgrading packages"
echo "!!!!!!!!!!!!!!!!!!"
sudo apt-get -y upgrade

# we need unixodbc to connect to pervasive
sudo apt-get install -y g++ unixodbc unixodbc-dev

# we could do this in python 
# python-dev is required for compiling the odbc extension
sudo apt-get install -y python python-dev python-pip
sudo pip install pyodbc

# install php so we can build this in php
sudo apt-get install -y php5 php5-odbc php5-mysql php5-cli curl php5-curl

