# Introduction #

The application uses a simple table structure that is easily ported to any relational database system that is supported by the DB package (including MySQL, MSSQL, Oracle, SQLite, Sybase, and PostgreSQL). While the included SQL script is for MySQL, you can easily recreate this table in any other language.

In the libs folder of the application, there is an SQL script to create a table for your application. We've included a helper script for MySQL (mysql\_helper.sh) to assist you in creating the datasource and setting up a user for the database. You will need to remember this information to add in the configs/config.inc.php file.

If you need (or want) to use an existing database, there is no need to run this script.

```
$> cd PATH_TO_APPLICATION
$> ./mysql_helper.sh
```

This script will prompt you for a database name, a database user for the database, a password for the user, then for an account that is authorized to create the datasource.

## Table Structure ##
The following is an ANSI-SQL script that _should_ work on most RDBMS systems:

```
CREATE TABLE locations(
    uid BIGINT PRIMARY KEY NONCLUSTERED,
    x INT NOT NULL,
    y INT NOT NULL,
    floor INT NOT NULL,
    updated DATETIME NOT NULL
)
GO
```

If you need to use table prefixes be sure and adjust the script above accordingly.  You will also need to make the adjustment in config.inc.php.

In order to guarantee that the data Facebook sends will be stored correctly, the _uid_ column should be capable of storing 64-bit integers.

# Other Pages #
  * [Set up web server](ServerSetup.md)
  * [Set up Smarty](SmartySetup.md)
  * [Set up Locater](LocaterSetup.md)