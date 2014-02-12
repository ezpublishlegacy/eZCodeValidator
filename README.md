eZCodeValidator
===============
Code validator is a configurable and extensible framework for running validators over project code. Multiple validators (for PHP, JavaScript, eZPublish templates etc.) are included in the project.

Installation
---------------
This project needs PHP 5.3+ to run. Besides that, some validators require additional tools:

- JSValidator requires jshint - http://www.jshint.com/ (may be installed with npm)
- CSSValidator requires csshint - https://github.com/stubbornella/csslint (may be installed with npm)
- eZTemplateValidator requires eZPublish - http://ez.no

Configuration
---------------
Project may be configured using JSON file (default configuration is included - `config.default.json`). This way, log printers and validators may be set up.

**Log printers** write messages from validation to console/files etc. Types of messages (error, warning, debug etc.) that should be printed can be specified for each printer. Besides that, each log printer takes custom configuration.
Default configuration file uses two printers:
- `CLILogPrinter` for displaing errors and failures on the console,
- and `FileLogPrinter` for writing more detailed log to the `/tmp/ezcv_errors.log` file.

**Validators** check the code and push messages to log printers. For each validator blacklist and whitelist of file extensions should be provided. This allows to target specific files with specific validators (e.g. JavaScript files, ".js", should be validated with JSValidator). Besides that, each validator takes custom configuration.

Console parameters
--------------
`run.php` script takes couple of console parameters:
- `--dirs` list of directories to scan for files to validate
- `--recusive` if directories should be scanned recursively
- `--files` list of files to validate
- `--config` path to configuration (if not set, default configuration will be used)

ezcv.sh
---------------
This script must be run from eZ Publish root folder. It takes two parameters:
- `-g` git repository folder (if not set, 'git/' will be used)
- `-c` path to configuration (if not set, default configuration will be used)

It will use `run.php` to validate or format any modified files in the git repository.

Recommended use is to add one or more config files to the project, and then add a shell alias for easy access:

`alias projectvalidate='/www/eZCodeValidator/ezcv.sh -c extension/project/doc/config.validate.json`
