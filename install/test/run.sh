#!/bin/sh

#if [ "fischer" != "$USER" ]; then
#	echo "This script must run as user fischer!"
#	exit 1
#fi

. ../pervasive/bashrc

echo
echo
echo Python:
cd python
python pyodbc-test.py
cd ..

echo
echo
echo PhP:
cd php
php php-odbc-test.php
cd ..
