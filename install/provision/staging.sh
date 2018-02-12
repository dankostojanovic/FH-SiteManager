#!/bin/sh

# Shortcut command to log in or provision staging server:

# Usage:

# To log in:
# ./staging.sh

# To provision:
# ./staging.sh provision

PROVISION=${1-login}
IPADDR=52.5.222.104
if [ "provision" = $PROVISION ]; then
	echo "Provisioning server"
	sh remote-install.sh $IPADDR ../keys/FDI.pem staging
else 
	echo "Log in to server"
	ssh -i ../keys/FDI.pem ubuntu@$IPADDR
fi
