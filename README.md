# sy/project

A skeleton to start a new website. Build with [sy/bootstrap](https://github.com/syframework/bootstrap)

## Requirements

- Docker

## Installation

Start a new project with

```
composer create-project sy/project [DIRECTORY]
```

## Composer scripts

The project lifecycle is one command, wired to the three Composer events that
can happen on an install or an update:

```json
"post-create-project-cmd": ["@sy install"],
"post-install-cmd": ["@sy install"],
"post-update-cmd": ["@sy install"]
```

`vendor/bin/sy` is shipped by `sy/bootstrap`. `sy install` is idempotent: every
configuration file that is missing is generated, the plugin files are copied
when absent and refreshed when their vendor copy is newer, and the Sass build
and the migrations run only when they are actually needed. Nothing is
remembered in a state file — every decision is derived from the filesystem.

| Command | Effect |
|---|---|
| `composer create-project sy/project [DIRECTORY]` | Installs the dependencies, then runs `sy install`: asks for the missing INI files, builds the assets, migrates the database. |
| `composer install` | Installs the dependencies, then `sy install`. |
| `composer update` | Updates the dependencies, then `sy install`. |
| `composer require sy/bootstrap-<plugin>` | Installs the plugin, then `sy install` runs through `post-update-cmd`. |
| `composer install-plugin <plugin> [version]` | Shorthand for `composer require sy/bootstrap-<plugin>[:version]`. |
| `composer sy <verb>` | Any installer verb directly: `install`, `plan`, `sync`, `build`, `db`, `doctor`. |
| `composer sy plan` | The full install pass without executing anything: prints every command it would run. |
| `protected/vendor/bin/sy <verb>` | The same installer, without Composer: flags are not intercepted, which is what a deploy script needs. |
| `composer db [task]` | Flyway on the core schema and on every plugin schema. `migrate` by default; `info`, `repair`, `clean`, `validate` and `baseline` are accepted. |
| `composer build` | Rebuilds the CSS and JS assets on demand. |
| `composer doctor` | Exits non zero when a plugin file, `assets/css/app.css` or a configuration file is missing. |
| `composer chown` | Gives the current user back the files created as root by the Docker build (WSL). |
| `composer format`, `composer check` | PHP_CodeSniffer fixer and checker. |

The installer modifiers are **positional**, so Composer forwards them:
`composer sy install quiet` works, while `composer sy install --quiet` would see
Composer consume the flag — and `composer sy install --dry-run` would silently
run a real install. Use the `plan` verb for a dry run, and the positional
modifiers `quiet`, `no-build`, `no-db`, `no-templates`:

```bash
composer sy plan                          # what would install do, without doing it
composer sy install quiet                 # no output, for a script
composer sy install no-build no-db        # files only
composer db info                          # positional task, no separator needed
protected/vendor/bin/sy install --dry-run # direct call: flags are kept as-is
```

## Configuration

Everything lives in `composer.json`, under `extra.sy`:

```json
"extra": {
	"sy": {
		"build": "docker run --pull always --rm -v \"$PWD\":/syproject -w /syproject node:slim gulp build",
		"ini": {
			"database": { "file": "protected/conf/database.ini", "input": { "host": { "question": "MySQL hostname" } } },
			"smtp": { "file": "protected/conf/smtp.ini", "input": { "host": { "question": "SMTP hostname" } } }
		},
		"install": ["php protected/bin/seed.php"]
	}
}
```

- `build` is the shell command compiling the project assets; it runs in the
  project root and only when a plugin SCSS file is newer than
  `assets/css/app.css`;
- `ini` lists the configuration files `genini` generates when they are missing,
  with the questions asked to the developer;
- `install` lists the shell commands run once, just after the INI generation, so
  a later `sy install` does nothing.

Add an entry to `ini` for a project-specific file, and a command to `install`
for anything `genini` cannot express.

## Plugin files

`sy install` applies the same rules to every installed plugin
(`vendor/sy/bootstrap-*`):

- `assets/` are copied, or refreshed when the vendor copy is newer;
- `templates/` are copied only when the file is missing: once copied, a
  template belongs to the project and is never overwritten;
- the Sass build runs only when a plugin ships `scss/` (or a `.scss` file under
  `templates/`), and only when `assets/css/app.css` is older than every plugin
  SCSS file;
- the migrations run per plugin schema, each with its own
  `flyway_<plugin>_history` table.

`composer doctor` is the cheapest way to check a deployment: it exits non zero
when a plugin asset is missing from the project, which is exactly the failure a
`git clean` or a stale deploy produces.

## Create configuration files

The installer guides you through these files: each entry of `extra.sy.ini` is
asked for when the file is missing, on the first `composer install` (or
`composer sy install`).

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