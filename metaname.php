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

function debug( $message ) {
	$f = fopen( '/tmp/metaname-module.log', 'a' );
	fwrite( $f, '  '.date('c').'  '.$message."\n" );
	fclose( $f );
}

function show( $message, $value ) {
  debug( $message.'  '.var_export($value,TRUE) );
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
  debug('metaname_RegisterDomain '.var_export($p,TRUE));
  /*
   array (
    'domainid' => '4',
    'sld' => 'kiwiping-test',
    'tld' => 'co.nz',
    'regperiod' => '1',
    'registrar' => 'metaname',
    'ns1' => 'ns1.metaname.net',
    'ns2' => 'ns2.metaname.net',
    'ns3' => 'ns3.metaname.net',
    'ns4' => '',
    'ns5' => '',
    'transfersecret' => '',
    'userid' => '1',
    'id' => '1',
    'firstname' => 'REDACTED',
    'lastname' => 'REDACTED',
    'companyname' => '',
    'email' => 'REDACTED',
    'address1' => 'REDACTED',
    'address2' => '',
    'city' => 'Chch',
    'state' => 'Canterbury',
    'postcode' => 'REDACTED',
    'countrycode' => 'NZ',
    'country' => 'NZ',
    'countryname' => 'New Zealand',
    'phonecc' => '64',
    'phonenumber' => 'REDACTED',
    'notes' => '',
    'password' => 'REDACTED',
    'twofaenabled' => '',
    'currency' => '1',
    'defaultgateway' => '',
    'cctype' => '',
    'cclastfour' => '',
    'securityqid' => '0',
    'securityqans' => '',
    'groupid' => '0',
    'status' => 'Active',
    'credit' => '0.00',
    'taxexempt' => '',
    'latefeeoveride' => '',
    'overideduenotices' => '',
    'separateinvoices' => '',
    'disableautocc' => '',
    'emailoptout' => '1',
    'overrideautoclose' => '0',
    'language' => '',
    'lastlogin' => 'Date: 11/09/2013 14:21<br>IP Address: REDACTED<br>Host: REDACTED',
    'billingcid' => '0',
    'fullstate' => 'Canterbury',
    'dnsmanagement' => '',
    'emailforwarding' => '',
    'idprotection' => '',
    'adminfirstname' => 'REDACTED',
    'adminlastname' => 'REDACTED',
    'admincompanyname' => '',
    'adminemail' => 'REDACTED',
    'adminaddress1' => 'REDACTED',
    'adminaddress2' => '',
    'admincity' => 'Chch',
    'adminfullstate' => 'Canterbury',
    'adminstate' => 'Canterbury',
    'adminpostcode' => 'REDACTED',
    'admincountry' => 'NZ',
    'adminphonenumber' => 'REDACTED',
    'fullphonenumber' => 'REDACTED',
    'adminfullphonenumber' => 'REDACTED',
    'original' => 
    array (
      'domainid' => '4',
      'sld' => 'kiwiping-test',
      'tld' => 'co.nz',
      'regperiod' => '1',
      'registrar' => 'metaname',
      'ns1' => 'ns1.metaname.net',
      'ns2' => 'ns2.metaname.net',
      'ns3' => 'ns3.metaname.net',
      'ns4' => '',
      'ns5' => '',
      'transfersecret' => NULL,
      'userid' => '1',
      'id' => '1',
      'firstname' => 'REDACTED',
      'lastname' => 'REDACTED',
      'companyname' => '',
      'email' => 'REDACTED',
      'address1' => 'REDACTED',
      'address2' => '',
      'city' => 'Chch',
      'state' => 'Canterbury',
      'postcode' => 'REDACTED',
      'countrycode' => 'NZ',
      'country' => 'NZ',
      'countryname' => 'New Zealand',
      'phonecc' => 64,
      'phonenumber' => 'REDACTED',
      'notes' => '',
      'password' => 'REDACTED',
      'twofaenabled' => false,
      'currency' => '1',
      'defaultgateway' => '',
      'cctype' => '',
      'cclastfour' => '',
      'securityqid' => '0',
      'securityqans' => '',
      'groupid' => '0',
      'status' => 'Active',
      'credit' => '0.00',
      'taxexempt' => '',
      'latefeeoveride' => '',
      'overideduenotices' => '',
      'separateinvoices' => '',
      'disableautocc' => '',
      'emailoptout' => '1',
      'overrideautoclose' => '0',
      'language' => '',
      'lastlogin' => 'Date: 11/09/2013 14:21<br>IP Address: REDACTED<br>Host: REDACTED',
      'billingcid' => '0',
      'fullstate' => 'Canterbury',
      'dnsmanagement' => false,
      'emailforwarding' => false,
      'idprotection' => false,
      'adminfirstname' => 'REDACTED',
      'adminlastname' => 'REDACTED',
      'admincompanyname' => '',
      'adminemail' => 'REDACTED',
      'adminaddress1' => 'REDACTED',
      'adminaddress2' => '',
      'admincity' => 'Chch',
      'adminfullstate' => 'Canterbury',
      'adminstate' => 'Canterbury',
      'adminpostcode' => 'REDACTED',
      'admincountry' => 'NZ',
      'adminphonenumber' => 'REDACTED',
      'fullphonenumber' => 'REDACTED',
      'adminfullphonenumber' => 'REDACTED',
    ),
    'Endpoint' => 'https://test.metaname.net/api/1.1',
    'AccountRef' => 'joe',
    'APIKey' => 'yeah, right',
  )
  */
  debug('metaname_RegisterDomain');
  $domain_name = $p['sld'].'.'.$p['tld'];
  show('  domain_name: ', $domain_name);
  $term = 12 * $p['regperiod'];
  show('  term: ', $term);
  $registrant_contact = array(
    'name' =>              $p['firstname'].' '.$p['lastname'],
    'email_address' =>     $p['email'],
    'organisation_name' => $p['companyname'],
    'postal_address' =>     array(
                              'line1' =>        $p['address1'],
                              'line2' =>        $p['address2'],
                              'city' =>         $p['city'],
                              'region' =>       $p['state'],
                              'postal_code' =>  $p['postcode'],
                              'country_code' => $p['country'],
                            ),
    'phone_number' =>       array(
                              'country_code' => $p['phonecc'],
                              'area_code' =>    NULL,
                              'local_number' => $p['phonenumber'],
                            ),
    'fax_number' =>         NULL,
  );
  show('  registrant_contact: ', $registrant_contact);
  $contacts = array(
    'registrant_contact' => $registrant_contact,
    'admin_contact' =>      $registrant_contact,
    'technical_contact' =>  $registrant_contact,
  );
  show('  contacts: ', $contacts);
  $name_servers = array();
  for( $n = 1;  $n <= 5;  $n++ ) {
    $ns = $p['ns'.$n];
    if ( $ns != '' ) {
      array_push( $name_servers, array('name' => $ns) );
    }
  }
  show('  name_servers: ', $name_servers);
  $udai = register_domain_name( $domain_name, $term, $contacts, $name_servers );
	return array( 'error' => 'Not implemented' );
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
