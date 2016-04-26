extension-release [UNDER DEVELOPMENT]
==========

A PHP class for creating browser extension releases.

***Only Chrome is currently supported.***

## Features

- Packs/Obfuscate JS files
- Changes version in manifest files
- Creates a folder with the release
- Packs the release (ready to upload to webstore) 

## Installation

Simply run `composer require soft4good/extension-release`.

## Usage

```php
<?php
	require 'vendor/autoload.php';

	$oRelease = Soft4Good\ExtensionRelease\Factory::create( './release.json' ); // see release.json file...
```