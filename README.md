
# Github:

https://github.com/joussin/package-template


# Installation via Composer:

````json
{
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/joussin/package-template.git"
    }
  ],
  "require": {
    "joussin/package-template": "dev-develop"
  }
}
````



--- 


# Configuration via Composer

### Basic Package composer.json:

````json
{
  "name": "joussin/package-template",
  "type": "library",
  "license": "MIT",
  "homepage": "https://github.com/joussin/package-template",
  "description": "Development package template",
  "keywords": ["package"],
  "authors": [
    {
      "name": "Joussin Stéphane",
      "email": "joussin@live.com",
      "role": "Developer"
    }
  ],
  
  "require": {
    "php": ">=7.2.0"
  },
  
  "require-dev": {
    "symfony/var-dumper": "^6.3"
  },
  
  "autoload": {
    "psr-4": {
      "Joussin\\Package\\": "src/"
    },
    "files": [
      "src/helpers/functions.php"
    ]
  },
  "extra": {
    "branch-alias": {
      "dev-master": "2.0.x-dev"
    },
    "laravel": {}
  },
  "minimum-stability": "dev",
  "prefer-stable": true,
  "conflict": {},
  "suggest": {},
  "scripts": {},
  "config": {
    "sort-packages": true
  }
}
````

### Psr Implementation

For a Psr Implementation, add :

````json
{

  "require": {
    "psr/container": "^1.1.1|^2.0.1"
  },

  "provide": {
    "psr/container-implementation": "1.1|2.0"
  }
}
````

### Script - Tests - Code Clean - Custom script

TODO: voir en détail

For code test & code clean or any script, add :

````json
{
  "require-dev": {
    "phpunit/phpunit": "^9.5.11|^10.0",
    "phpstan/phpstan": "^1.10",
    "friendsofphp/php-cs-fixer": "^3.5",
    "vimeo/psalm": "^4.7.3"
  },

  "scripts": {
    "phpcs": "phpcs",
    "phpstan": "phpstan analyse",
    "phpunit": "phpunit --no-coverage",
    "psalm": "psalm",
    "test": [
      "@phpcs",
      "@phpstan",
      "@psalm",
      "@phpunit"
    ]
  }
}
````

For a Custom bin/script, add :

````json
{
  "bin": [
    "bin/script"
  ],
  
  "scripts-descriptions": {
    "command_name": "Script description"
  },
  "scripts": {
    "command_name": ""
  }
}

````
