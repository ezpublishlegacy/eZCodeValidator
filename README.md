eZCodeValidator
===============
Code validator is a configurable and extesable framework for running validators over project code. Multiple validators (for PHP, JavaScript, eZPublish templates etc.) are included in the project.

Instalation
---------------
This project needs PHP 5.4+ to run. Besides that, some validators require additional tools:

- JSValidator requires jshint - http://www.jshint.com/ (may be installed with npm)
- CSSValidator requires csshint - https://github.com/stubbornella/csslint (may be installed with npm)
- eZTemplateValidator requires eZPublish - http://ez.no

Configuration
---------------
Project may be configured using JSON file (default configuration is included in project - `config.default.json`). This way, log printers and validators may be set up.

Log printers write messages from validation to console/files etc. Types of messages (error, warning, debug etc.) that should be printed can be specified for each printer. Besides that, each log printer takes custom configuraiton.

Validators check the code and push messages to log printers. For each validator blacklist and whitelist of file extensions should be provided. This allows to target specific files with specific validators (e.g. JavaScript files, ".js", should be validated with JSValidator). Besides that, each validator takes custom configuration.

Console parameters
--------------
`run.php` script takes couple of console parameters:
- `--dirs` list of directories to scan for files to validate
- `--recusive` if directories should be scanned recursively
- `--files` list of files to validate
- `--config` path to configuration (if not set, default configuraiton will be used)

ezcv.sh
---------------
This bash script can be runned in any directory with git repository set up. It reads list of files to be commited and runs validators on them (using `php run.php --files`).