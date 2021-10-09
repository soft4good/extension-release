<?php

namespace Soft4Good\ExtensionRelease;

abstract class Release implements ReleaseInterface
{
  const OBUSCATOR_IO_OBFUSCATOR = 'ObfuscatorIO';
  const TOLU_PACKER_OBFUSCATOR  = 'TholuPacker';
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

  /*
   * String $name Extension name, used as release directory name: <name>-<version> format
   */
  public $name;

  /*
   * Boolean $package If we should package the extension
   */
  protected $package = false;

  /*
   * String $obfuscator Obfuscator to use
   */
  protected $obfuscator = 'TholuPacker';

  public function __construct( $releaseData = null )
  {
    if ( $releaseData  ) {
      $this->releaseBasePath = $releaseData['releases_path'] ? rtrim( $releaseData['releases_path'], ' /' ) : './_releases';
      $this->codePath        = $releaseData['path'] ? rtrim( ( $releaseData['path'] ), ' /' ) : '';

      $this->name       = $releaseData['name']        ? $releaseData['name']       : '';
      $this->excludes   = $releaseData['excludes']    ? $releaseData['excludes']   : [];
      $this->js         = $releaseData['js']          ? $releaseData['js']         : [];
      $this->css        = $releaseData['css']         ? $releaseData['css']        : [];
      $this->version    = $releaseData['version']     ? $releaseData['version']    : 0;
      $this->package    = $releaseData['package']     ? $releaseData['package']    : false;
      $this->obfuscator = $releaseData['obfuscator']  ? $releaseData['obfuscator'] : '';
    }
  }

  // http://php.net/manual/de/function.copy.php
  private function recurse_copy( $src, $dst )  // TODO: refactor this...
  {
    $dir = opendir( $src );

    if ( file_exists( $dst ) ) {
      throw new \Exception( "Release already exists. Path: $dst" );
    }
    mkdir( $dst, 0777, true );

    while( false !== ( $file = readdir( $dir ) ) ) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if ( is_dir($src . '/' . $file) ) {
          $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
        }
        else {
          copy( $src . '/' . $file, $dst . '/' . $file );
        }
      }
    }
    closedir($dir);
  }

  protected function prepareDir( $name = '' )
  {
    if ( $name ) {
      $name = preg_replace( '/[^A-Za-z0-0]/s', '-', trim( $name, ' -' ) );
    }
    else {
      $name = $this->name;
    }

    // construct release path
    $this->releasePath  = $this->releaseBasePath . '/';
    $this->releasePath .= $name ? $name  . '-' : '';
    $this->releasePath .= $this->version;

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
        $jsPackedCode = $jsCode = file_get_contents( $jsPath );
        
        $obfuscatorFn = "obfuscateWith" . $this->obfuscator;
        if (method_exists($this, $obfuscatorFn)) {
          $jsPackedCode = call_user_func_array([$this, $obfuscatorFn], [$jsCode]);
        }

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
    // only the first part increases infinitely
    $this->version = (int)( str_replace( '.', '', $this->version ) ) + 1;
    $this->version = str_pad( $this->version, 4, '0', STR_PAD_LEFT );
    $this->version = preg_replace( '/^([0-9]+)([0-9]{1})([0-9]{1})([0-9]{1})$/', '\1.\2.\3.\4', $this->version );

    return $this->version;
  }

  private function obfuscateWithObfuscatorIO($jsCode)
  {
    return \ObfuscatorIO\Obfuscator::obfuscate($jsCode);
  }

  private function obfuscateWithTholuPacker($jsCode)
  {
    $packer = new \Tholu\Packer\Packer($jsCode, 'None', false, false, false );
    return $packer->pack();
  }

} // Soft4Good\ExtensionRelease\Release