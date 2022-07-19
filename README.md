Marketo API for PHP
===================

Wrapper for Marketo API

![Banner]()

<p align="center">
<a href="LICENSE"><img src="https://img.shields.io/github/license/bmenking-wng/marketo_api" alt="Software License"></img></a>
</p>

## Installation

Installation by [Composer](https://getcomposer.org/):

Modify your composer.json by adding the following section (if it doesn't already exist):

```yaml
...
    "require": {
        ...
        "worldnewsgroup/marketo_api": "dev:master",
        ...
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/bmenking-wng/marketo_api.git"
        }
    ],
...
```

Then run composer update:

```bash
$ composer update
```

## Usage/Quickstart

```php
use WorldNewsGroup\Marketo\Environment;
use WorldNewsGroup\Marketo\Model\Lead;

Environment::configure('client_id', 'client_secret', 'munchkin_id');

$result = Lead::getLeadById($leadId);

$lead = $result->lead();

echo "Hello, {$lead->getFirstName()}\n";

```



