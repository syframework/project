# sy/project

A skeleton to start a new website. Build with [sy/bootstrap](https://github.com/syframework/bootstrap)

## Requirements

- Docker

## Installation

Start a new project with

```
composer create-project sy/project [DIRECTORY]
```

## Create configuration files

A command line interface will guide you for generating these configuration files when you create a new project.

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

When a new project is created, the database is created with this SQL script: ```protected/sql/V1__init.sql```

We use [Flyway](https://flywaydb.org/) to manage database changes. Here is the composer custom command:
```
composer db [FLYWAY COMMAND]
```

Flyway commands:
- migrate
- info
- repair
- clean
- validate
- baseline

Example:
```
composer db migrate
```

## Build CSS & JS

We have a composer custom command for building CSS and JS assets:
```
composer build
```

But it's recommended to install NodeJS and Gulp if you have to rebuild frequently the CSS or JS.

Go to the project folder and install project dependencies
```
npm install
```

To build css (```protected/scss/app.scss``` -> ```assets/css/app.css```)
```
gulp css
```

To build js (```protected/js/*.js``` -> ```assets/js/app.js```)
```
gulp js
```

## Check/format PHP coding style

Check the coding style
```
composer check [FILE or DIRECTORY]
```

Format the coding style
```
composer format [FILE or DIRECTORY]
```

Example:
```
composer format protected/src
```

Coding standard ruleset used: [sy/coding-standard](https://github.com/syframework/coding-standard)