#!/bin/sh

# we will run everything as the user fischer
sudo adduser --disabled-password --gecos "" fischer

# add user fischer to group which can access pervasive
sudo usermod -a -G pvsw fischer
