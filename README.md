[![Maintainability](https://api.codeclimate.com/v1/badges/7e83e9f82ea1b19e1574/maintainability)](https://codeclimate.com/github/Orange-Management/phpOMS/maintainability)

# General

The **phpOMS** framework provides many features to manage and create a web application and its backend. Additionally to the framework features it also includes many utils and api integrations that provides additional functionality.

## Features

Features this framework provides are:

* Account/Group management
* Permission management
* Asset management
* Business logic (e.g. sales, marketing, etc.)
* Console support
* WebSocket support
* Event management
* Database management
* Cache management
* Dispatcher
* Router
* Authentication
* Localization
* Logging (console/backend)
* Request/Response management
* Math (e.g. matrix, forecasting, optimization, geometry, stochastics, etc.)
* Module management
* Uri 
* Utils (e.g. barcodes, comporession, unit converter, jobqueue, git, etc.)
* Value validation
* View management
* Stdlib (e.g. graph, map, queue, enum, etc.)

# Development Status

The framework reached a point where it can be already used albeit many features are still under development.

# Unit Tests

Run the following command for unit tests:

```
php .\phpunit.phar --bootstrap .\phpOMS\tests\Bootstrap.php .\phpOMS\tests\
```