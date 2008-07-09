#! /bin/bash

#
# Helper script to set up MySQL datasource
# $Id$
#

## Begin
clear
echo "Welcome to the MySQL Setup Script"
echo "This will set up the MySQL database for your application"
echo ""

read -p "Database Name [fbathenaeum]: " MYSQLDB
read -p "Database User [fbuser]: " MYSQLNEWUSER
read -p "User Password: " -s MYSQLNEWPASS

echo ""
read -p "MySQL Host [localhost]: " MYSQLHOST
read -p "MySQL Root User [root]: " MYSQLUSER
read -p "MySQL Root Password: " -s MYSQLPASS
echo ""

## Set defaults
if [ -z $MYSQLDB ]; then
	MYSQLDB=fbathenaeum
fi
if [ -z $MYSQLNEWUSER ]; then
	MYSQLNEWUSER=fbuser
fi
if [ -z $MYSQLHOST ]; then
	MYSQLHOST=localhost
fi
if [ -z $MYSQLUSER ]; then
	MYSQLUSER=root
fi

## Process database
mysqladmin -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS create $MYSQLDB
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -e "GRANT SELECT,INSERT,UPDATE,DELETE ON $MYSQLDB.* TO '$MYSQLNEWUSER'@'$MYSQLHOST' IDENTIFIED BY '$MYSQLNEWPASS' WITH GRANT OPTION"
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -e "FLUSH PRIVILEGES"
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -D $MYSQLDB < libs/mysql.sql
