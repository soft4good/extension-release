ExtensionRelease
================

A package to automate browser extension releases/deployments.

***Only Chrome is currently supported.***

## Features

- Understands release recipes from release.json files
- Packs/Obfuscate JS files
- Changes version in manifest files
- Creates a folder with the release
- Packs the release (ready to upload to webstore) 

### Coming soon

- Integration with Google Webstore Publish API (https://developer.chrome.com/webstore/using_webstore_api)
- Integration with Firefox Signing API (https://olympia.readthedocs.io/en/latest/topics/api/signing.html)

## Installation

Simply run `composer require soft4good/extension-release`.

## Usage

```php
<?php
  require 'vendor/autoload.php';

  try {
    Soft4Good\ExtensionRelease\Factory::create( './release.sample.json' );
  }
  catch( Exception $exception ) {
    die( $exception->getMessage() );
  }
```

### Command Line
```
$ php path/to/extension-release.php path/to/release.json
```
