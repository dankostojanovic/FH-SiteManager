#!/bin/sh

#cd /vagrant/provision/

#sh ./install.sh

sudo apt-get install -y apache2 php7.0 libapache2-mod-php7.0
sudo apt-get update
sudo apt-get -y upgrade
sudo apt-get install -y apache2 php7.0 libapache2-mod-php7.0
cd /etc/apache2
sudo rm /etc/apache2/apache2.conf 
sudo cp /vagrant/install/apache2.conf /etc/apache2/apache2.conf

cd ~
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y mysql-server
sudo curl -s https://getcomposer.org/installer | php
sudo apt install -y composer
sudo apt-get install -y php-xml
sudo apt install -y zip unzip php7.0-zip
sudo apt-get install php7.0-mysql
sudo a2enmod rewrite
sudo service apache2 restart

cd /vagrant/fischer_api/
composer install
sudo apt-get install php7.0-ldap

cd /etc/mysql/mysql.conf.d
sudo rm /etc/mysql/mysql.conf.d/mysqld.cnf 
sudo cp /vagrant/install/mysqld.cnf /etc/mysql/mysql.conf.d/mysqld.cnf
sudo service mysql restart

cd /var
sudo rm -rf www
sudo ln -s /vagrant www
cd /vagrant/fischer_api
sudo service apache2 restart


sudo mysql -u root --execute "grant all privileges on *.* to 'webdev'@'%' identified by 'W3bD3vP4ss';
grant all privileges on *.* to 'webdev'@'localhost' identified by 'W3bD3vP4ss';
flush privileges;
create database fischer_api;
use fischer_api;
exit"
mysql -u webdev -p'W3bD3vP4ss' fischer_api < ../install/Dump20180105.sql

cd /vagrant/fischer_api/
php ./vendor/propel/propel/bin/propel.php reverse "mysql:host=staging.fischermgmt.com;dbname=fischer_api;user=fischerhomes;password=O7y3zGW7szZZ"
php ./vendor/propel/propel/bin/propel.php model:build
php ./vendor/propel/propel/bin/propel.php config:convert
composer dump-autoload
