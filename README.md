# apigility-raml-validator

This is a tool to help validate your Apigility implementation against a RAML
specification. This way you can make sure the implementation matches what the
specification requirements.

In comparison with similar tools this application
does not check the output of the api but the code structure directly.

> Please note that this tool is in early stages of development.

## Table of contents
- [Installation](#installation)
- [Usage](#usage)
- [Validation](#validation)

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

## Validation

This is the list of fields from the RAML specification currently being validated

| RAML          | Apigility                                        |
| ------------- | ------------------------------------------------ |
| title         | Check a module matching `title` exists           |
| version       | Check a version folder matching `version` exists |
| resource\*    | Check a route has been defined for the `resource`|

\* only top level resources are detected for now
