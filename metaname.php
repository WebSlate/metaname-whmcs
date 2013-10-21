<?php
require_once 'JsonRpcClient.php';

function WS_jsonRequest( $method, $p ) {
	$log  = 'metaname.log.html';
	$date = date( 'd/m/y H:i:s' );
	try {
		$api = new JsonRpcClient( 'https://' . ( ( $p['TestSite'] === 'no' ) ? '' : 'test.' ) . 'metaname.net/api/1.1' );
		$arg = func_get_args();
		unset( $arg[0], $arg[1] );
		$result  = call_user_func_array( array( $api, $method ), array_merge( array( $p['AccountRef'], $p['APIKey'] ), $arg ) );
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
	return ( substr( $str, -strlen( $tld ) ) === $tld );
}

function debug( $message ) {
	$f = fopen( '/tmp/metaname-module.log', 'a' );
	fwrite( $f, '  ' . date( 'c' ) . '  ' . $message . "\n" );
	fclose( $f );
}

function show( $message, $value ) {
	debug( $message . '  ' . var_export( $value, TRUE ) );
}

function name_servers_specified_by( $params ) {
	$name_servers = array();
	for ( $n = 1; $n <= 5; $n++ ) {
		$ns = $params['ns' . $n];
		if ( $ns != '' ) {
			array_push( $name_servers, array(
				'name'        => $ns,
				'ip4_address' => NULL,
				'ip6_address' => NULL
			) );
		}
	}
	return $name_servers;
}

function str_rpartition( $string, $delimiter ) {
	$i = strrpos( $string, $delimiter );
	if ( $i === false ) {
		return array( $string, NULL );
	} else {
		return array( substr( $string, 0, $i ), substr( $string, $i + 1 ) );
	}
}

function copy_contact_details( $contact, &$values, $contact_type ) {
	$name_parts                          = str_rpartition( $contact->name, ' ' );
	$values[$contact_type]['First Name'] = $name_parts[0];
	$values[$contact_type]['Last Name']  = $name_parts[1];
}

class MetanameHelper {
	public function domain_name_in( $params )
	{
		return $params['sld'] . '.' . $params['tld'];
	}
}
$metaname = new MetanameHelper();

function metaname_getConfigArray() {
	return array(
		'AccountRef' => array(
			'FriendlyName' => 'Account Reference',
			'Description'  => 'Enter your Metaname Account Reference here.',
			'Size'         => '4',
			'Type'         => 'text'
		),
		'APIKey' => array(
			'FriendlyName' => 'API Key',
			'Description'  => 'Enter your Metaname API Key here.',
			'Size'         => '40',
			'Type'         => 'text'
		),
		'TestSite' => array(
			'FriendlyName' => 'Test Site',
			'Description'  => 'Tick to use the Metaname Test Site.',
			'Type'         => 'yesno'
		)
	);
}

function metaname_RegisterDomain( $p ) {
	debug( 'metaname_RegisterDomain ' . var_export( $p, TRUE ) );
	debug( 'metaname_RegisterDomain' );
	$domain_name = $p['sld'] . '.' . $p['tld'];
	show( '  domain_name: ', $domain_name );
	$term = 12 * $p['regperiod'];
	show( '  term: ', $term );
	$registrant_contact = array(
		'name'              => $p['firstname'] . ' ' . $p['lastname'],
		'email_address'     => $p['email'],
		'organisation_name' => $p['companyname'],
		'postal_address'    => array(
			'line1'             => $p['address1'],
			'line2'             => $p['address2'],
			'city'              => $p['city'],
			'region'            => $p['state'],
			'postal_code'       => $p['postcode'],
			'country_code'      => $p['country']
		),
		'phone_number'      => array(
			'country_code'      => $p['phonecc'], # FIXME: At least try to parse the area code from the phone number
			'area_code'         => '9',
			'local_number'      => $p['phonenumber']
		),
		'fax_number'        => NULL
	);
	show( '  registrant_contact: ', $registrant_contact );
	$contacts = array(
		'registrant' => $registrant_contact,
		'admin'      => $registrant_contact,
		'technical'  => $registrant_contact
	);
	show( '  contacts: ', $contacts );
	$name_servers = name_servers_specified_by( $p );
	show( '  name_servers: ', $name_servers );
	$udai = WS_jsonRequest( 'register_domain_name', $p, $domain_name, $term, $contacts, $name_servers );
	return array( 'info' => 'The UDAI is ' . $udai );
}

function metaname_TransferDomain( $p ) {
	debug( 'metaname_TransferDomain ' . var_export( $p, TRUE ) );
	return array( 'error' => 'Not implemented' );
}

function metaname_RenewDomain( $p ) {
	if ( $p['regperiod'] < 2 ) {
		if ( WS_isTld( $p['tld'], 'uk' ) ) {
			return array( 'error', '.uk domains must be registered for at least 2 years' );
		}
		if ( WS_isTld( $p['tld'], 'mobi' ) ) {
			return array( 'error', '.mobi domains must be registered for at least 2 years'  =);
		}
	}
	$result = WS_jsonRequest( $p, 'renew_domain_name', ( $p['sld'] . $p['tld'] ), ( $p['regperiod'] * 12 ) );
	if ( !is_array( $result ) ) {
		return array( 'error', $result );
	}
}

function d( $message, $value ) {
	echo $message . ': ' . var_export( $value, TRUE ) . '.';
}

function _named( $name, $domains ) {
	#d( '_named', $name );
	foreach ( $domains as $domain ) {
		#show( 'd', $domain );
		#d( 'n', $domain->name );
		if ( $domain->name == $name ) {
			return $domain;
		}
	}
	return NULL;
}

function metaname_GetNameservers( $p ) {
	#debug( 'metaname_GetNameservers ' . var_export( $p, TRUE ) );
	$domains     = WS_jsonRequest( 'domain_names', $p );
	#d( 'p', $p );
	#show( 'dms', $domains );
	$domain_name = $p['sld'] . '.' . $p['tld'];
	#var_export( $domain_name );
	$domain      = _named( $domain_name, $domains );
	#var_export( $domain );
	if ( $domain != NULL ) {
		$values = array();
		for ( $i = 0; $i < count( $domain->name_servers ); $i += 1 ) {
			$values['ns' . ( 1 + $i )] = $domain->name_servers[$i]->name;
		}
		return $values;
	} else {
		return array( 'error' => 'This domain does not appear to be in your portfolio' );
	}
}

function metaname_SaveNameservers( $p ) {
	#debug( 'metaname_SaveNameservers ' . var_export( $p, TRUE ) );
	$domain_name  = $p['sld'] . '.' . $p['tld'];
	$name_servers = name_servers_specified_by( $p );
	WS_jsonRequest( 'update_name_servers', $p, $domain_name, $name_servers );
	return NULL;
}

function metaname_GetContactDetails( $p ) {
	debug( 'metaname_GetContactDetails ' . var_export( $p, TRUE ) );
	$domain_name = $p['sld'] . '.' . $p['tld'];
	# Put your code to get WHOIS data here
	$domains     = WS_jsonRequest( 'domain_names', $p );
	$domain      = _named( $domain_name, $domains );
	show( 'domain', $domain );
	if ( $domain != NULL ) {
		$values = array();
		copy_contact_details( $domain->contacts->registrant, $values, 'Registrant' );
		copy_contact_details( $domain->contacts->admin, $values, 'Admin' );
		copy_contact_details( $domain->contacts->technical, $values, 'Tech' );
		show( 'vl', $values );
		return $values;
	} else {
		return array( 'error' => 'This domain does not appear to be in your portfolio' );
	}
}

function encode_contact( $params, $contact_type ) {
	return array( 'name' => $params['contactdetails'][$contact_type]['First Name'] . ' ' . $params['contactdetails'][$contact_type]['Last Name'] );
}

function metaname_SaveContactDetails( $p ) {
	global $metaname;
	debug( 'metaname_SaveContactDetails ' . var_export( $p, TRUE ) );
	show( 'ms', $metaname );
	$contacts = array(
		'registrant' => encode_contact( $p, 'Registrant' ),
		'admin'      => encode_contact( $p, 'Admin' ),
		'technical'  => encode_contact( $p, 'Tech' )
	);
	WS_jsonRequest( 'update_contacts', $p, $metaname->domain_name_in( $p ), $contacts );
}

#https://github.com/WebSlate/metaname-whmcs/blob/7e182bccbd57cd1cbae542badbbc0b081a29e5bb/metaname.php
