# apigility-raml-validator

This is a tool to help validate your Apigility implementation against a RAML
specification. This way you can make sure the implementation matches what the
specification requirements. In comparison with similar tools this application
does not check the output of the api but the code structure directly.

> Please note that this tool is in early stages of development.

## Table of contents
- [Installation](#installation)
- [Usage](#usage)

## Installation
```php
composer require --dev "robertboloc/apigility-raml-validator"
chmod +x vendor/bin/apigility-raml-validator
```

## Usage
```php
vendor/bin/apigility-raml-validator [--help] [-s spec, --spec spec] [-p project, --project project]
```

### Options
##### spec (-s | --spec)
Path to the RAML specification file

##### target (-p | --project)
Path to the Apigility project containing the source code

##### help (--spec)
Display the usage message
