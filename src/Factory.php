<?php

namespace Soft4Good\ExtensionRelease;

class Factory
{
  static function create( $releaseDefinition )
  {
    if ( is_string( $releaseDefinition ) ) { // release.json file path
      try {
        $releaseDefinition = json_decode( file_get_contents( $releaseDefinition ), true );
      }
      catch( Exception $exception ) {
        throw $exception;
      }
    }

    $release = null;
    if ( $releaseDefinition ) {
      foreach( $releaseDefinition as $browser => $releases ) {
        if ( $releases ) {
          foreach( $releases as $releaseData ) {
            if ( $releaseData ) {
              $className = __NAMESPACE__ . '\\' . trim( preg_replace( '/\s/', '', ucwords( $browser ) ) );

              if ( class_exists( $className ) ) {
                $release = new $className( $releaseData );
              }
              else {
                throw new \Exception( "[$browser] release is not implemented. Expected class name: $className" );
              }

              $release->create();
            }
          }
        }
      }
    }
    else {
      throw new \Exception( "Invalid releases definition." );
    }

    return $release;
  }
}