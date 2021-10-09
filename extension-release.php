<?php // TODO: more options...
  
  require 'lib/ObfuscatorIO/ObfuscatorIO.php';
  require 'vendor/autoload.php';

  if ( !$argv[1] )  {
    die( "ERROR: Please provide a release file" );
  }

  try {
    $release = Soft4Good\ExtensionRelease\Factory::create( $argv[1] );
  }
  catch( \Exception $exception ) {
    die( $exception->getMessage() );
  }