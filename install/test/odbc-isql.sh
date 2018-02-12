#!/bin/sh

if [ "fischer" != "$USER" ]; then
	echo "This script must run as user fischer!"
	exit 1
fi

. pervasive/bashrc

/usr/local/psql/bin/isql64 Pervasive -v

#isql -v pervasive

