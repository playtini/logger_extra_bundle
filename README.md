Logger Extra Bundle
-------------------

Add logs extra information with [symfony/monolog-bundle](https://symfony.com/doc/current/logging.html).
Using Gelf and Logstash to send/receive logs via UDP protocol

Installation
============

### Step 1: Prepare config

Make sure [symfony/monolog-bundle](https://symfony.com/doc/current/logging.html) is installed.

Add env vars:
* `SERVICE_NAME`: service name
* `LOGSTASH_HOST`: logstash host
* `LOGSTASH_PORT`: logstash port

Add to `config/service.yaml`:
```yaml
parameters:
    env(SERVICE_NAME): your_service_name
    env(LOGSTASH_HOST): 127.0.0.1
    env(LOGSTASH_PORT): 12201

    service_name: '%env(string:SERVICE_NAME)%'
    logstash_host: '%env(string:LOGSTASH_HOST)%'
    logstash_port: '%env(string:LOGSTASH_PORT)%'
```

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require playtini/logger-extra-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Playtini\LoggerExtraBundle\ServiceLoggerExtraBundle::class => ['all' => true],
];
```

## Usage

#### Additions
Add custom data in each log entry.
```yaml
service_logger_extra:
    processor:
        additions:
            key_addition_var_1: value_addition_var_1
            key_addition_var_2: value_addition_var_2
```

#### Loggers

By default, the bundle provides several loggers:
* on_command (create a log entry with the cli data)
* on_request (create a log entry with the request/response data)

Add config to include them:
```yaml
service_logger_extra:
  logger:
    on_command: true
    on_request: true
```