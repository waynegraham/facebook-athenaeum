#!/bin/bash

#
# Helper script to set up MySQL datasource
# $Id$
#

## Begin Script
clear
echo "Welcome to the MySQL Setup Script."
echo "This will setup the MySQL Database"
echo ""

## Prompt for Database values
read -p "New Database Name [fbathenaeum]: " MYSQLDB
read -p "New Database User [fbuser]: " MYSQLNEWUSER
read -p "New User Password: " -s MYSQLNEWPASS
echo ""
read -p "MySQL Host [localhost]: " MYSQLHOST
read -p "MySQL Root User [root]: " MYSQLUSER
read -p "MySQL Root Password: " -s MYSQLPASS
echo ""


## Set Defaults if selected
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


## Process creating mysql user and database
mysqladmin -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS create $MYSQLDB
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -e "GRANT SELECT,INSERT,UPDATE,DELETE ON $MYSQLDB.* TO '$MYSQLNEWUSER'@'$MYSQLHOST' IDENTIFIED BY '$MYSQLNEWPASS' WITH GRANT OPTION"
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -e "FLUSH PRIVILEGES"
mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASS -D $MYSQLDB < libs/mysql.sql


echo "MySQL Setup is now Complete"

