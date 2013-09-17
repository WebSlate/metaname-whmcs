<?php
require_once 'JsonRpcClient.php';

function WS_jsonRequest( $method, $p ) {
	$args = func_get_args();
	unset( $args[0], $args[1] );
	$api = new JsonRpcClient( 'https://' . ( ( $p['TestSite'] === 'no' ) ? '' : 'test.' ) . 'metaname.net/api/1.1' );
	try {
		return json_decode( call_user_func_array( array( $api, $method ), array_merge( array( $p['AccountRef'], $p['APIKey'] ), $args ) ) );
	} catch( Exception $e ) {
		return $e->getMessage();
	}
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