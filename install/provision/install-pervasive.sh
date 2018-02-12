#!/bin/sh

# this script must be run with the provision directory as cwd
# or the same directory as install.sh

# see this instruction on setting it up with php:
# http://cs.pervasive.com/forums/p/14171/48890.aspx#48890

# extract the tar as in the instructions
#old 
#FILENAME="Pervasive.SQL-Client-Core-11.30-061.000.x86_64.tar.gz"
#upgraded version of psql 
FILENAME="Pervasive.SQL-11.31-101.000.x86_64.tar.gz"
sudo cp ../pervasive/$FILENAME /usr/local/
cd /usr/local
sudo tar -xzf $FILENAME
sudo rm $FILENAME

# run their install scripts
cd /usr/local/psql/etc
sudo sh clientpreinstall.sh
sudo sh clientpostinstall.sh

# odbc ini settings for pervasive
# see http://www.unixodbc.org/odbcinst.html
# see https://tron2001.wordpress.com/2014/09/09/psql-64-bit-linux-client-installation/

# install data source using odbcinst
sudo odbcinst -i -d -f /usr/local/psql/etc/odbcinst.ini

# get variables from config file
DSNIP="10.1.1.52"
DSNPORT="1583"
DSNDB="INFOSYS"
if [ -f ../src/config/config.sh ]; then
	. ../src/config/config.sh
fi

# create our own ODBC.ini using variables for ip, port, db
cat << EOF > ~/odbc.ini
[ODBC Data Sources]
Pervasive=Pervasive ODBC Interface
[Pervasive]
Driver=Pervasive ODBC Interface
Description=Pervasive ODBC Interface: $DSNIP:$DSNPORT/$DSNDB
ServerName=$DSNIP:$DSNPORT
DBQ=$DSNDB
ArrayFetchOn=0
OpenMode=0
PvTranslate=None
EOF

# copy our odbc.ini to etc to configure system dsn
sudo cp ~/odbc.ini /etc/odbc.ini
sudo chown root:root /etc/odbc.ini
sudo chmod 644 /etc/odbc.ini
rm ~/odbc.ini

# pervasive wants to see the ini in its own etc directory
cd /usr/local/psql/etc
sudo ln -s -f /etc/odbc.ini
