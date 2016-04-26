extension-release
==========

A PHP class for creating browser extension releases.

***Only Chrome is currently supported.***

## Features

- Packs/Obfuscate JS files
- Changes version in manifest files
- Creates a folder with the release
- Packs the release (ready to upload to webstore) 

## Installation

Simply run `composer require reis4g/extension-release`.

## Usage

```php
<?php
	require 'vendor/autoload.php';

	$oRelease = new ExtensionRelease( './release.json' ); // see release.json file...
```