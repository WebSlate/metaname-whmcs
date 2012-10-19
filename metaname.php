<?php
require_once 'JsonRpcClient.php';
$api = new JsonRpcClient( 'https://www.metaname.co.nz/api' );

function metaname_getConfigArray() {
	return array(
		'AccountRef' => array(
			'Type' => 'text',
			'Size' => '4',
			'Description' => 'Enter your Metaname Account Reference here',
		),
		'APIKey' => array(
			'Type' => 'text',
			'Size' => '40',
			'Description' => 'Enter your Metaname API Key here',
		),
	);
}

function metaname_RegisterDomain( $p ) {
	return array( 'error', 'Not Implemented' );
}

function metaname_TransferDomain( $p ) {
	return array( 'error', 'Not Implemented' );
}

function metaname_RenewDomain( $p ) {
	$tld = $p['tld'];
	$sld = $p['sld'];
	$regperiod = $p['regperiod'];
	return $api->renew_domain_name( $p['AccountRef'], $p['APIKey'], ( $sld.$tld ), ( ( substr( $tld, -2 ) === 'nz' ) ? $regperiod : ( $regperiod * 12 ) ) );
}

function metaname_GetNameservers( $p ) {
	return array( 'error', 'Not Implemented' );
}

function metaname_SaveNameservers( $p ) {
	return array( 'error', 'Not Implemented' );
}

function metaname_GetContactDetails( $p ) {
	return array( 'error', 'Not Implemented' );
}

function metaname_SaveContactDetails( $p ) {
	return array( 'error', 'Not Implemented' );
}