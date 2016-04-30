<?php

namespace Soft4Good\ExtensionRelease;

abstract class Release
{

  /*
   * String $codePath Path to the extension code
   */
  protected $codePath;

  /*
   * Array $css Relative path (from extension root)  to CSS files to pack
   */
  protected $css;

  /*
   * Array $js Relative path (from extension root) to JS files to pack
   */
  protected $js;

  /*
   * String $version If not set will auto-increment current version
   */
  protected $version;

  /*
   * String $releaseBasePath Path to releases folder
   */
  protected $releaseBasePath;

  /*
   * Array $excludes Paths to exclude from release
   */
  protected $excludes;

  /*
   * String $releasePath Path to this release's folder
   */
  public $releasePath;

  public function __construct( $releaseData = null )
  {
    if ( $releaseData  ) {
      $this->releaseBasePath = trim( ( $releaseData['releases_path'] || './_releases' ),  ' /' );
      $this->codePath        = trim( ( $releaseData['path']  || '' ), ' /' );

      $this->name     = $releaseData ['name']         || '';
      $this->excludes = $releaseData ['excludes']     || [];
      $this->js       = $releaseData ['js']           || [];
      $this->css      = $releaseData ['css']          || [];
      $this->version  = $releaseData ['version']      || '';
    }
  }

  // http://php.net/manual/de/function.copy.php
  private function recurse_copy( $src,$dst ) { // TODO: refactor this...
    $dir = opendir($src);
    mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          recurse_copy($src . '/' . $file,$dst . '/' . $file);
        }
        else {
          copy($src . '/' . $file,$dst . '/' . $file);
        }
      }
    }
    closedir($dir);
  }

  protected function prepareDir( $name )
  {
    $name = preg_replace( '/[^A-Za-z0-0]/s', '-', trim( $name, ' -' ) );

    $this->releasePath = $this->releaseBasePath . '/' . $name  . '-' . $this->version;
    try {
      $this->recurse_copy( $this->codePath, $this->releasePath );
    }
    catch( \Exception $exception ) {
      throw $exception;
    }
  }

  protected function packJS()
  {
    foreach( $this->js as $jsPath ) {
      $jsPath = $this->releasePath . '/' . $jsPath;
      try {
        $sJsCode = file_get_contents( $jsPath );
        $oPacker = new Tholu\Packer\Packer( $sJsCode, 'None', true, true, false );
        $jsPackedCode = $oPacker->pack();

        $jsFile = fopen( $jsPath, 'w+' );
        fwrite( $jsFile, $jsPackedCode );
        fclose( $jsFile );
      }
      catch( \Exception $exception ) {
        throw $exception;
      }
    }
  }

  protected function packCSS()
  {
    // TODO: not implemented...
  }

  protected function autoIncrementVersion()
  {
    $versionParts = explode( '.', $this->version );
    $versionParts = array_pad( $versionParts, 4, '0' ); // X.X.X.X version format

    // only the first value can increase infinitely
    for( $i = 0; $i < count( $versionParts ) - 2; $i++ ) {
      $increment = $versionParts[$i + 1] == 9 ? 1 : 0;
      $versionParts[$i]     += $increment;
      $versionParts[$i + 1] += $increment ? -9 : 1;
    }

    $this->version = implode( '.', $versionParts );

    return $this->version;
  }

} // Soft4Good\ExtensionRelease\Release