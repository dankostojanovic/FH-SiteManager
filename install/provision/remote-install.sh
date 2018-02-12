#!/bin/sh
IPADDR=$1
KEY=$2
BRANCH=${3-staging}

# Errors will stop the script
set -e

# To Future Sherman: I'm taking the easy way out and using the 
# default ubuntu user to do git clone. This isn't ideal, but it
# means I won't have to upload ssh keys, or set up multiple 
# deploy keys. Just use the FDI.pem from AWS. I hope this
# doesn't cause you any trouble.

# To Past Sherman: You still have to upload an ssh key to enable
# ubuntu to clone from github. The FDI.pem isn't for ubuntu to call
# out, it's in authorized_keys to enable calling in. This has 
# caused me some trouble. I just hope there isn't something else 
# missing which makes us need another account.

# Make sure user has provided a key file which exists:
if [ ! -f $KEY ]; then
	echo "SSH key file not found! (normally FDI.pem)"
	exit 2
fi

# Config will be loaded from files in 
# src/config/staging or from
# src/config/master
# based on the $BRANCH variable.

# Make sure config exists already:
FILE="../src/config/$BRANCH/config.json"
if [ ! -f "$FILE" ]; then 
	echo "$FILE: file not found!"
	exit 2
fi
FILE="../src/config/$BRANCH/config.sh"
if [ ! -f "$FILE" ]; then 
	echo "$FILE: file not found!"
	exit 2
fi

# verify github ssh keys
# http://serverfault.com/questions/447028/non-interactive-git-clone-ssh-fingerprint-prompt
# http://serverfault.com/a/701637/33170
# https://help.github.com/articles/what-are-github-s-ssh-key-fingerprints/
ssh-keyscan github.com > githubKey
TEST=$(ssh-keygen -lf githubKey)
# FINGERPRINT="16:27:ac:a5:76:28:2d:36:63:1b:56:4d:eb:df:a6:48"
FINGERPRINT="SHA256:nThbg6kXUpJWGl7E1IGOCspRomTxdCARLviKw6E5SY8";
TESTSTR="2048 $FINGERPRINT github.com (RSA)"
if [ "$TESTSTR" != "$TEST" ]; then
	echo "Boo! SSH key fingerprints do not match! Someone may be doing a MITM attack!"
	echo "$TEST"
	echo "$TESTSTR"
	exit 1
fi

# upload known github host key which matches fingerprint
scp -i $KEY githubKey ubuntu@$IPADDR:~/github
rm githubKey

# upload private key for ubuntu so it can clone from github
scp -i $KEY ../keys/FDI_ubuntu ubuntu@$IPADDR:~/.ssh/id_rsa
scp -i $KEY ../keys/FDI_ubuntu.pub ubuntu@$IPADDR:~/.ssh/id_rsa.pub

# first upload configuration
scp -i $KEY ../src/config/$BRANCH/config.* ubuntu@$IPADDR:~/

# run commands to clone the repo and get ready to provision
ssh -i $KEY ubuntu@$IPADDR <<ENDSSH

# copy github key to known_hosts
mkdir -p ~/.ssh
chmod 700 ~/.ssh
cat ~/github >> ~/.ssh/known_hosts
chmod 600 ~/.ssh/known_hosts

# as ubuntu, make /var/jobs
sudo mkdir -p /var/jobs
sudo chown ubuntu:ubuntu /var/jobs
sudo chmod 755 /var/jobs

# adding update command
echo "before update command in remote-install.sh "
echo 
sudo apt-get update

# as ubuntu, install git
sudo apt-get -y install git

# as ubuntu, git clone in /var/jobs
cd /var/jobs
if [ -d "data-integrator" ]; then
	cd ./data-integrator
	echo "Checking out $BRANCH..."
	git fetch --prune && git checkout $BRANCH && git pull
else
	echo "Cloning repo..."
	git clone git@github.com:FHomes/data-integrator.git
	cd ./data-integrator
	echo "Checking out $BRANCH..."
	git checkout $BRANCH
fi

# copy uploaded config to config directory
cp ~/config.* /var/jobs/data-integrator/src/config/

# as ubuntu, run provisioning script in /var/jobs/data-integrator/provision
cd /var/jobs/data-integrator/provision
sh ./install.sh

ENDSSH
