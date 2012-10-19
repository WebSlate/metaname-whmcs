<?php
require_once 'JsonRpcClient.php';
$api = new JsonRpcClient( 'https://www.metaname.co.nz/api' );

function metaname_getConfigArray() {
	$configarray = array(
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
	return $configarray;
}

function metaname_GetNameservers( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];

	$values['ns1'] = $nameserver1;
	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_SaveNameservers( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$nameserver1 = $params['ns1'];
	$nameserver2 = $params['ns2'];
	$nameserver3 = $params['ns3'];
	$nameserver4 = $params['ns4'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_GetRegistrarLock( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];

	if ( $lock == '1' ) {
		$lockstatus='locked';
	} else {
		$lockstatus='unlocked';
	}
	return $lockstatus;
}

function metaname_SaveRegistrarLock( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	if ($params['lockenabled']) {
		$lockstatus = 'locked';
	} else {
		$lockstatus = 'unlocked';
	}

	$values['error'] = $Enom->Values['Err1'];
	return $values;
}

function metaname_GetDNS( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];

	$hostrecords = array( array( 'hostname' => 'ns1', 'type' => 'A', 'address' => '10.0.0.1' ) );
	return $hostrecords;

}

function metaname_SaveDNS( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	foreach ($params['dnsrecords'] AS $key=>$values) {
		$hostname = $values['hostname'];
		$type = $values['type'];
		$address = $values['address'];

	}
	$values['error'] = $Enom->Values['Err1'];
	return $values;
}

function metaname_RegisterDomain( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$regperiod = $params['regperiod'];
	$nameserver1 = $params['ns1'];
	$nameserver2 = $params['ns2'];
	$nameserver3 = $params['ns3'];
	$nameserver4 = $params['ns4'];
	$RegistrantFirstName = $params['firstname'];
	$RegistrantLastName = $params['lastname'];
	$RegistrantAddress1 = $params['address1'];
	$RegistrantAddress2 = $params['address2'];
	$RegistrantCity = $params['city'];
	$RegistrantStateProvince = $params['state'];
	$RegistrantPostalCode = $params['postcode'];
	$RegistrantCountry = $params['country'];
	$RegistrantEmailAddress = $params['email'];
	$RegistrantPhone = $params['phonenumber'];
	$AdminFirstName = $params['adminfirstname'];
	$AdminLastName = $params['adminlastname'];
	$AdminAddress1 = $params['adminaddress1'];
	$AdminAddress2 = $params['adminaddress2'];
	$AdminCity = $params['admincity'];
	$AdminStateProvince = $params['adminstate'];
	$AdminPostalCode = $params['adminpostcode'];
	$AdminCountry = $params['admincountry'];
	$AdminEmailAddress = $params['adminemail'];
	$AdminPhone = $params['adminphonenumber'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_TransferDomain( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$regperiod = $params['regperiod'];
	$transfersecret = $params['transfersecret'];
	$nameserver1 = $params['ns1'];
	$nameserver2 = $params['ns2'];
	$RegistrantFirstName = $params['firstname'];
	$RegistrantLastName = $params['lastname'];
	$RegistrantAddress1 = $params['address1'];
	$RegistrantAddress2 = $params['address2'];
	$RegistrantCity = $params['city'];
	$RegistrantStateProvince = $params['state'];
	$RegistrantPostalCode = $params['postcode'];
	$RegistrantCountry = $params['country'];
	$RegistrantEmailAddress = $params['email'];
	$RegistrantPhone = $params['phonenumber'];
	$AdminFirstName = $params['adminfirstname'];
	$AdminLastName = $params['adminlastname'];
	$AdminAddress1 = $params['adminaddress1'];
	$AdminAddress2 = $params['adminaddress2'];
	$AdminCity = $params['admincity'];
	$AdminStateProvince = $params['adminstate'];
	$AdminPostalCode = $params['adminpostcode'];
	$AdminCountry = $params['admincountry'];
	$AdminEmailAddress = $params['adminemail'];
	$AdminPhone = $params['adminphonenumber'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_RenewDomain( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$regperiod = $params['regperiod'];

	$api->renew_domain_name( $params['AccountRef'], $params['APIKey'], ( $sld.$tld ), ( ( substr( $tld, -2 ) === 'nz' ) ? $regperiod : ( $regperiod * 12 ) ) );

	$values['error'] = 'Not Fully Implemented';
	return $values;
}

function metaname_GetContactDetails( $params ) {
/*
	$tld = $params['tld'];
	$sld = $params['sld'];

	$values['Registrant']['First Name'] = $firstname;
	$values['Registrant']['Last Name'] = $lastname;
	$values['Admin']['First Name'] = $adminfirstname;
	$values['Admin']['Last Name'] = $adminlastname;
	$values['Tech']['First Name'] = $techfirstname;
	$values['Tech']['Last Name'] = $techlastname;
*/
	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_SaveContactDetails( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	# GetContactDetails()
	$firstname = $params['contactdetails']['Registrant']['First Name'];
	$lastname = $params['contactdetails']['Registrant']['Last Name'];
	$adminfirstname = $params['contactdetails']['Admin']['First Name'];
	$adminlastname = $params['contactdetails']['Admin']['Last Name'];
	$techfirstname = $params['contactdetails']['Tech']['First Name'];
	$techlastname = $params['contactdetails']['Tech']['Last Name'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_GetEPPCode( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];

	$values['eppcode'] = $eppcode;

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_RegisterNameserver( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$nameserver = $params['nameserver'];
	$ipaddress = $params['ipaddress'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_ModifyNameserver( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$nameserver = $params['nameserver'];
	$currentipaddress = $params['currentipaddress'];
	$newipaddress = $params['newipaddress'];

	$values['error'] = 'Not Implemented';
	return $values;
}

function metaname_DeleteNameserver( $params ) {
	$tld = $params['tld'];
	$sld = $params['sld'];
	$nameserver = $params['nameserver'];

	$values['error'] = 'Not Implemented';
	return $values;
}