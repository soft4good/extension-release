<?php

namespace Soft4Good\ExtensionRelease;

class Chrome extends Release implements ReleaseInterface
{
  public function __construct( $releaseData = null )
  {
    parent::__construct( $releaseData );
  }

  protected function setVersion( $version )
  {
    $this->version = $version;
  }

  public function create()
  {
    // read manifest.json
    $manifestPath = $this->codePath . "/manifest.json";
    try {
      $manifest = json_decode( file_get_contents( $manifestPath ), true );
    }
    catch( \Exception $exception ) {
      throw new \Exception( "manifest.json file is not found or corrupted...\n" . $exception->getMessage() );
    }

    // update manifest version
    if ( !$this->version ) {
      $this->version = $manifest['version'] ? $manifest['version'] : 0;
      $manifest['version'] = $this->autoIncrementVersion();
    }

    // write manifest.json
    $manifestFile = fopen( $manifestPath, 'w+' );
    fwrite( $manifestFile, NiceJson::format( json_encode( $manifest ) ) );
    fclose( $manifestFile );

    // copy files to release folder
    if ( !$this->name ) {
      $this->name = $manifest['short_name'];
    }
    $this->prepareDir();

    // pack JS and CSS
    $this->packCSS();
    $this->packJS();
  }

  // getters / setters
  public function setJS( $js )   { $this->js  = $js; }
  public function setCSS( $css ) { $this->css = $css; }

  public function addJS( $js )   { $this->js  = array_merge( $this->js,  $js ); }
  public function addCSS( $css ) { $this->css = array_merge( $this->css, $css ); }

} // Soft4Good\ExtensionRelease\Chrome