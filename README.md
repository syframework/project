# sy/project

A skeleton to start a new website. Build with [sy/bootstrap](https://github.com/syframework/bootstrap)

## Installation

Start a new project with

```bash
$ composer create-project sy/project [DIRECTORY]
```

## Create configuration files

Create the file [DOCUMENT ROOT]/protected/conf/database.ini

Example:

```
host = localhost
port = 3306
dbname = mydatabase
username = myuser
password = mypassword
charset = utf8mb4
```

Create the file [DOCUMENT ROOT]/protected/conf/smtp.ini

Example:

```
host = smtp.gmail.com
username = user@example.com 
password = mypassword
port = 587
encryption = tls
```

## Create database

1. Create your database
2. Create your user and give permissions
3. Create tables

Use the database installation script: ```protected/sql/install.sql```

## Build CSS & JS

Go to the project folder and install project dependencies
```
npm install
```

To build css (```protected/scss/app.scss``` -> ```assets/css/app.css```)
```
gulp css
```

To build js (```protected/js/app.js``` -> ```assets/js/app.js```)
```
gulp js
```