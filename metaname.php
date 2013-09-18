<?php
require_once 'JsonRpcClient.php';

function WS_jsonRequest( $method, $p ) {
	$log  = 'metaname.log.html';
	$date = date( 'd/m/y H:i:s' );
	try {
		$api = new JsonRpcClient( 'https://' . ( ( $p['TestSite'] === 'no' ) ? '' : 'test.' ) . 'metaname.net/api/1.1' );
		$arg = func_get_args();
		unset( $arg[0], $arg[1] );
		$result  = json_decode( call_user_func_array( array( $api, $method ), array_merge( array( $p['AccountRef'], $p['APIKey'] ), $arg ) ) );
		$message = var_export( $result, TRUE );
		$entry   = <<<HTML
<h2>Registrar Response</h2>
<p><strong>Date:</strong> {$date}</p>
<h3>Message:</h3>
<pre>{$message}</pre>
HTML;
	} catch ( Exception $exception ) {
		$result  = FALSE;
		$code    = $exception->getCode();
		$file    = $exception->getFile();
		$line    = $exception->getLine();
		$message = $exception->getMessage();
		$trace   = $exception->getTraceAsString();
		$entry   = <<<HTML
<h2>Exception</h2>
<p><strong>Date:</strong> {$date}</p>
<p><strong>Code:</strong> {$code}</p>
<p><strong>File:</strong> {$file}</p>
<p><strong>Line:</strong> {$line}</p>
<p><strong>Message:</strong> {$message}</p>
<h3>Stack Trace:</h3>
<pre>{$trace}</pre>
HTML;
	}
	$handle = fopen( $log, 'a' );
	if ( $handle ) {
		$i     = 1;
		$len   = strlen( $entry );
		$old   = fread( $handle, $len );
		$final = filesize( $log ) + $len;
		rewind( $handle );
		while ( ftell( $handle ) < $final ) {
			fwrite( $handle, $entry );
			$entry = $old;
			$old   = fread( $handle, $len );
			fseek( $handle, $i * $len );
			$i++;
		}
	}
	return $result;
}

function WS_isTld( $str, $tld ) {
	return ( substr( $str, - strlen( $tld ) ) === $tld );
}

function metaname_getConfigArray() {
	return array(
		'AccountRef'        => array(
			'FriendlyName'  => 'Account Reference',
			'Description'   => 'Enter your Metaname Account Reference here.',
			'Size'          => '4',
			'Type'          => 'text',
		),
		'APIKey'            => array(
			'FriendlyName'  => 'API Key',
			'Description'   => 'Enter your Metaname API Key here.',
			'Size'          => '40',
			'Type'          => 'text',
		),
		'TestSite'          => array(
			'FriendlyName'  => 'Test Site',
			'Description'   => 'Tick to use the Metaname Test Site.',
			'Type'          => 'yesno',
		),
	);
}

function metaname_RegisterDomain( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_TransferDomain( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_RenewDomain( $p ) {
	if ( $p['regperiod'] < 2 ) {
		if ( WS_isTld( $p['tld'], 'uk'   ) ) { return array( 'error',   '.uk domains must be registered for at least 2 years' ); }
		if ( WS_isTld( $p['tld'], 'mobi' ) ) { return array( 'error', '.mobi domains must be registered for at least 2 years' ); }
	}
	$result = WS_jsonRequest( $p, 'renew_domain_name', ( $p['sld'] . $p['tld'] ), ( $p['regperiod'] * 12 ) );
	if ( !is_array( $result ) ) {
		return array( 'error', $result );
	}
}

function metaname_GetNameservers( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_SaveNameservers( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_GetContactDetails( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_SaveContactDetails( $p ) {
	return array( 'error', 'Not implemented' );
}