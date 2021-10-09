<?php
  namespace ObfuscatorIO;

  class Obfuscator
  {
    static function obfuscate( $code, $options = [] )
    {
      $options = array_merge([
        'compact'                        => true,
        'selfDefending'                  => false,
        'disableConsoleOutput'           => true,
        'debugProtection'                => false,
        'debugProtectionInterval'        => false,
        'stringArray'                    => true,
        'rotateStringArray'              => true,
        'rotateStringArrayEnabled'       => true,
        'stringArrayThreshold'           => 0.8,
        'stringArrayThresholdEnabled'    => true,
        'stringArrayEncoding'            => 'base64',
        'stringArrayEncodingEnabled'     => true,
        'sourceMap'                      => false,
        'sourceMapMode'                  => 'off',
        'sourceMapBaseUrl'               => '',
        'sourceMapFileName'              => '',
        'sourceMapSeparate'              => false,
        'domainLock'                     => [],
        'reservedNames'                  => [],
        'reservedStrings'                => [],
        'seed'                           => 0,
        'controlFlowFlatteningThreshold' => 0.75,
        'controlFlowFlattening'          => false,
        'deadCodeInjectionThreshold'     => 0.4,
        'deadCodeInjection'              => false,
        'unicodeEscapeSequence'          => true,
        'renameGlobals'                  => false,
        'target'                         => 'browser-no-eval',
        'identifierNamesGenerator'       => 'hexadecimal',
        'identifiersPrefix'              => '',
        'transformObjectKeys'            => false,
      ], $options );

      $response = API::post( 'obfuscate', array(
        'code'    => $code,
        'options' => $options
      ));

      // extract code portion of response
      preg_match('/"code":"(.*)","sourceMap":/', $response, $matches);
      
      return $matches[1];
    }
  }