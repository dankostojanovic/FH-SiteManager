#!/bin/sh

echo "In the install.sh "
echo

sh ./install-packages.sh
sh ./install-pervasive.sh
sh ./install-fischer.sh

cd ../src/
sh ./makecron.sh
