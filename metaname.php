<?php
error_reporting( -1 );
ini_set( 'display_errors', TRUE );
ini_set( 'display_startup_errors', TRUE );

require_once 'JsonRpcClient.php';

function WS_jsonRequest( $method, $ref, $key ) {
	$args = func_get_args();
	unset( $args[0], $args[1], $args[2] );
	$api = new JsonRpcClient( 'https://www.metaname.co.nz/api' );
	try {
		return json_decode( call_user_func_array( array( $api, $method ), array_merge( array( $ref, $key ), $args ) ) );

	} catch( Exception $e ) {
		return $e->getMessage();
	}
}

function WS_isTld( $str, $tld ) {
	return ( substr( $str, - strlen( $tld ) ) === $tld );
}

function WS_fail( $s ) {
	return ( 'error', $s );
}

function WS_validTerm( $y, $t ) {
	// WHMCS returns years, no handling for months
	if ( $y < 2 ) {
		if ( WS_isTld( $t, 'mobi' ) ) { WS_fail( '.mobi domains must be registered for at least 2 years' ); }
		if ( WS_isTld( $t, 'uk'   ) ) { WS_fail(   '.uk domains must be registered for at least 2 years' ); }
	}
	if ( $y & 1 ) ) {
		if ( WS_isTld( $t, 'au'   ) ) { WS_fail(   '.au domains must be registered for an even number of years' ); }
		if ( WS_isTld( $t, 'mobi' ) ) { WS_fail( '.mobi domains must be registered for an even number of years' ); }
	}
	return TRUE;
}

function metaname_getConfigArray() {
	return array(
		'AccountRef'      => array(
			'Description' => 'Enter your Metaname Account Reference here',
			'Size'        => '4',
			'Type'        => 'text',
		),
		'APIKey'          => array(
			'Description' => 'Enter your Metaname API Key here',
			'Size'        => '40',
			'Type'        => 'text',
		)
	);
}

function metaname_RegisterDomain( $p ) {
	$result = WS_validTerm( $p['regperiod'], $p['tld'] );
	if ( is_array( $result ) ) { return $result; }
	foreach ( $p as $k => $v ) { if ( empty( $v ) ) { $p[$k] = NULL; } }
	$out = array(
		'domain'            => 'array'
			'name'              => $p['sld'].$p['tld'],
			'registrant_contact'     => array(
				'name'              => $p['firstname'].' '.$p['lastname'],
				'email_address'     => $p['email'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['address1'],
					'line2'             => $p['address2'],
					'city'              => $p['city'],
					'region'            => $p['state'],
					'postal_code'       => $p['postcode'],
					'country_code'      => $p['country'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['phonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'admin_contact'     => array(
				'name'              => $p['adminfirstname'].' '.$p['adminlastname'],
				'email_address'     => $p['adminemail'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['adminaddress1'],
					'line2'             => $p['adminaddress2'],
					'city'              => $p['admincity'],
					'region'            => $p['adminstate'],
					'postal_code'       => $p['adminpostcode'],
					'country_code'      => $p['admincountry'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['adminphonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'technical_contact'     => array(
				'name'              => $p['adminfirstname'].' '.$p['adminlastname'],
				'email_address'     => $p['adminemail'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['adminaddress1'],
					'line2'             => $p['adminaddress2'],
					'city'              => $p['admincity'],
					'region'            => $p['adminstate'],
					'postal_code'       => $p['adminpostcode'],
					'country_code'      => $p['admincountry'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['adminphonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'registration_term' => $p['regperiod'] * 12,
		),
	);
	for ( $i = 0; $i < 4; $i++ ) {
		$out['name_servers'][$i]['name'] = $p['ns'.$i+1];
	}
	if ( $p['TestMode'] === 'no' ) {
		$result = WS_jsonRequest( $p['AccountRef'], $p['APIKey'], 'register', json_encode( $out ) );
		if ( !is_array( $result ) ) { return array( 'error', $result ); }
	}
}

function metaname_TransferDomain( $p ) {
	$result = WS_validTerm( $p['regperiod'], $p['tld'] );
	if ( is_array( $result ) ) { return $result; }
	foreach ( $p as $k => $v ) { if ( empty( $v ) ) { $p[$k] = NULL; } }
	$out = array(
		'domain'            => 'array'
			'name'              => $p['sld'].$p['tld'],
			'registrant_contact'     => array(
				'name'              => $p['firstname'].' '.$p['lastname'],
				'email_address'     => $p['email'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['address1'],
					'line2'             => $p['address2'],
					'city'              => $p['city'],
					'region'            => $p['state'],
					'postal_code'       => $p['postcode'],
					'country_code'      => $p['country'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['phonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'admin_contact'     => array(
				'name'              => $p['adminfirstname'].' '.$p['adminlastname'],
				'email_address'     => $p['adminemail'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['adminaddress1'],
					'line2'             => $p['adminaddress2'],
					'city'              => $p['admincity'],
					'region'            => $p['adminstate'],
					'postal_code'       => $p['adminpostcode'],
					'country_code'      => $p['admincountry'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['adminphonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'technical_contact'     => array(
				'name'              => $p['adminfirstname'].' '.$p['adminlastname'],
				'email_address'     => $p['adminemail'],
				'organisation_name' => '',
				'postal_address'    => array(
					'line1'             => $p['adminaddress1'],
					'line2'             => $p['adminaddress2'],
					'city'              => $p['admincity'],
					'region'            => $p['adminstate'],
					'postal_code'       => $p['adminpostcode'],
					'country_code'      => $p['admincountry'],
				),
				'phone_number'      => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => $p['adminphonenumber'],
				),
				'fax_number'        => array(
					'area_code'         => '',
					'country_code'      => '',
					'local_number'      => '',
				),
			),
			'registration_term' => $p['regperiod'] * 12,
		),
		'udai'              => $p["transfersecret"],
	);
	for ( $i = 0; $i < 4; $i++ ) {
		$out['name_servers'][$i]['name'] = $p['ns'.$i+1];
	}
	if ( $p['TestMode'] === 'no' ) {
		$result = WS_jsonRequest( $p['AccountRef'], $p['APIKey'], 'transfer_in', json_encode( $out ) );
		if ( !is_array( $result ) ) { return array( 'error', $result ); }
	}
}

function metaname_RenewDomain( $p ) {
	$result = WS_validTerm( $p['regperiod'], $p['tld'] );
	if ( is_array( $result ) ) { return $result; }
	$result = WS_jsonRequest( $p['AccountRef'], $p['APIKey'], 'renew', ( $p['sld'].$p['tld'] ), ( $p['regperiod'] * 12 ) );
	if ( !is_array( $result ) ) { return array( 'error', $result ); }
}

function metaname_GetNameservers( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_SaveNameservers( $p ) {
	foreach ( $p as $k => $v ) { if ( empty( $v ) ) { $p[$k] = NULL; } }
	for ( $i = 0; $i < 4; $i++ ) {
		$out['name_servers'][$i]['name'] = $p['ns'.$i+1];
	}
	if ( $p['TestMode'] === 'no' ) {
		$result = WS_jsonRequest( $p['AccountRef'], $p['APIKey'], 'update_domain', json_encode( $out ) );
		if ( !is_array( $result ) ) { return array( 'error', $result ); }
	}
}

function metaname_GetContactDetails( $p ) {
	return array( 'error', 'Not implemented' );
}

function metaname_SaveContactDetails( $p ) {
	return array( 'error', 'Not implemented' );
}

function template_GetEPPCode( $p ) {
	$result = WS_jsonRequest( $p['AccountRef'], $p['APIKey'], 'new_auth_code', ( $p['sld'].$p['tld'] ) );
	if ( !is_array( $result ) ) { return array( 'error', $result ); }
	return $result;
}

function template_RegisterNameserver( $p ) {
	return array( 'error', 'Not implemented' );
}

function template_ModifyNameserver( $p ) {
	return array( 'error', 'Not implemented' );
}

function template_DeleteNameserver( $p ) {
	return array( 'error', 'Not implemented' );
}