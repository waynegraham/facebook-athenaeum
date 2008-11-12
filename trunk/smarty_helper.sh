#! /bin/bash

#
# Installs Smarty the the PHP include directory if it is not installed and
# adds the required PEAR packages
#
# PHPDIR 
#	Configuration directory for PHP
#
# SMARTYDIR
# 	Directory to store Smarty
#
# SMARTY_VER
#   Current version of Smarty (just the filename)
#
# $Id$
#

PHPDIR=`pear config-get php_dir`
SMARTYDIR="$PHPDIR/Smarty"
SMARTY_VER="Smarty-2.6.20"

if [ ! -d $SMARTYDIR ]
then
    echo "Downloading Smarty..."
    wget http://www.smarty.net/do_download.php?download_file=$SMARTY_VER.tar.gz
    
    echo "Extracting Smarty"
    tar -zxf $SMARTY_VER.tar.gz
    mkdir $SMARTYDIR
    
    echo "Moving Smarty to $SMARTYDIR"
    mv $SMARTY_VER/libs/* $SMARTYDIR
    
    echo "Cleaning up..."
    rm $SMARTY_VER.tar.gz
    rm -rf $SMARTY_VER
fi

echo "Updating PEAR packages//"

## Install PEAR packages
pear upgrade pear
pear install --onlyreqdeps DB
pear install --onlyreqdeps XML_RSS
