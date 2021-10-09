<?php
  namespace ObfuscatorIO;

  const BASE_URL = 'https://obfuscator.io';

  class API
  {
    static function initRequest( $sUrl )
    {
      $iRequest = curl_init();

      curl_setopt( $iRequest, CURLOPT_HEADER,          0 );
      curl_setopt( $iRequest, CURLOPT_FOLLOWLOCATION,  1 );
      curl_setopt( $iRequest, CURLOPT_RETURNTRANSFER,  1 );
      curl_setopt( $iRequest, CURLOPT_URL, $sUrl );

      return $iRequest;
    }

    static function get( $sEndpoint, $aParams = null )
    {
      // not needed at this point...
    }

    static function post( $sEndpoint, $aParams = null )
    {
      // construct API URL
      $sApiUrl = BASE_URL . "/$sEndpoint";

      // construct request body
      $jsonBody = json_encode($aParams);

      // Do the request
      $iRequest = self::initRequest( $sApiUrl );
      curl_setopt( $iRequest, CURLOPT_VERBOSE, 1 );
      curl_setopt( $iRequest, CURLOPT_HEADER,  1 );

      // TODO: fix this !!!!!!! SECURITY !!!!!!!
      curl_setopt($iRequest, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($iRequest, CURLOPT_SSL_VERIFYPEER, 0);
      // ---------------------------------------

      curl_setopt( $iRequest, CURLOPT_ENCODING, "");
      curl_setopt( $iRequest, CURLOPT_MAXREDIRS, 10 );
      curl_setopt( $iRequest, CURLOPT_TIMEOUT, 30 );
      curl_setopt( $iRequest, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
      curl_setopt( $iRequest, CURLOPT_CUSTOMREQUEST, "POST");        
      curl_setopt( $iRequest, CURLOPT_POSTFIELDS, $jsonBody);
      curl_setopt( $iRequest, CURLOPT_HTTPHEADER, array(
          "Accept: */*",
          "Accept-Encoding: gzip, deflate",
          "Cache-Control: no-cache",
          "Connection: keep-alive",
          "Host: obfuscator.io",
          "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36",
          "cache-control: no-cache",
          "Content-Type: application/json",
          'Content-Length: ' . strlen($jsonBody)
        )
      );
      $sResponse = curl_exec( $iRequest );

      return $sResponse;
    }

    static function delete( $sEndpoint, $sSite = SITE_QUIBIDS, $aParams = null )
    {
      // not needed at this point...
    }

    static function put( $sEndpoint, $sSite = SITE_QUIBIDS, $aParams = null )
    {
      // not needed at this point...
    }

    static function patch( $sEndpoint, $sSite = SITE_QUIBIDS, $aParams = null )
    {
      // not needed at this point...
    }

    static function head( $sEndpoint, $sSite = SITE_QUIBIDS, $aParams = null )
    {
      // not needed at this point...
    }

    static function options( $sEndpoint, $sSite = SITE_QUIBIDS, $aParams = null )
    {
      // not needed at this point...
    }

  }