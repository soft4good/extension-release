<?php
  // Check for required extensions
  if ( !function_exists( 'curl_init' ) )
    throw new Exception( 'ObfuscatorIO needs the CURL PHP extension.' );

  if ( !function_exists( 'json_decode' ) )
    throw new Exception( 'ObfuscatorIO needs the JSON PHP extension.' );

  // ObfuscatorIO API
  require_once( __DIR__ . '/ObfuscatorIO/Api.php' );

  // ObfuscatorIO API Objects
  require_once( __DIR__ . '/ObfuscatorIO/Obfuscator.php' );