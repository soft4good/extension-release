<?php

namespace Soft4Good\ExtensionRelease;

class Factory
{
  static function create( $release )
  {
    if ( is_string( $release ) ) { // release.json file path
      try {
        $release = json_decode( file_get_contents( $release ), true );
      }
      catch( Exception $exception) {
        throw $exception;
      }
    }

    $oRelease = null;
    foreach( $release as $browser => $releaseData ) {
      $className = __NAMESPACE__ . '\\' . ucwords( $browser );

      if ( class_exists( $className ) ) {
        $oRelease = new $className( $releaseData );
      }
      else {
        throw new \Exception( "$browser release is not implemented. Expected class name: $className" );
      }

      $oRelease->create();
    }

    return $oRelease;
  }
}