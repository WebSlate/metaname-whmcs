<?php
require_once 'JsonRpcClient.php';
$api = new JsonRpcClient( 'https://www.metaname.co.nz/api' );

function metaname_cGetError( $e )
{
	switch ( $e ) {
		case '-1':
			$error = 'Authentication failed';
		break; case '-2':
			$error = 'Bidding closed';
		break; case '-3':
			$error = 'Invalid bid';
		break; case '-4':
			$error = 'Invalid domain name';
		break; case '-5':
			$error = 'Domain name not yet found';
		break; case '-6':
			$error = 'No account default contact';
		break; case '-7':
			$error = 'Invalid term';
		break; case '-8':
			$error = 'Invalid contact';
		break; case '-9':
			$error = 'Invalid name server';
		break; case '-10':
			$error = 'Invalid URI';
		break; case '-11':
			$error = 'Transaction declined';
		break; case '-12':
			$error = 'DNS hosting not enabled';
		break; case '-13':
			$error = 'HTTP redirection is enabled';
		break; case '-14':
			$error = 'Domain name already exists';
		break; case '-15':
			$error = 'Invalid UDAI';
		break; case '-16':
			$error = 'Invalid DNS record';
		break; case '-17':
			$error = 'DNS record not found';
		break; case '-32000':
			$error = 'Internal server error';
		break; case '-32600':
			$error = 'Invalid JSON-RPC request';
		break; case '-32601':
			$error = 'Method not found';
		break; case '-32602':
			$error = 'Invalid method parameters';
		break; case '-32603':
			$error = 'Internal JSON-RPC error';
		break; case '-32700':
			$error = 'JSON parse error';
		break;
	}
	return $error;
}

function metaname_getConfigArray() {
	$configarray = array(
	 "AccountRef" => array( "Type" => "text", "Size" => "4", "Description" => "Enter your Metaname Account Reference here", ),
	 "APIKey" => array( "Type" => "text", "Size" => "40", "Description" => "Enter your Metaname API Key here", ),
	);
	return $configarray;
}

function metaname_GetNameservers($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	# Put your code to get the nameservers here and return the values below
	$values["ns1"] = $nameserver1;
	$values["ns2"] = $nameserver2;
    $values["ns3"] = $nameserver3;
    $values["ns4"] = $nameserver4;
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_SaveNameservers($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
    $nameserver1 = $params["ns1"];
	$nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
	$nameserver4 = $params["ns4"];
	# Put your code to save the nameservers here
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_GetRegistrarLock($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	# Put your code to get the lock status here
	if ($lock=="1") {
		$lockstatus="locked";
	} else {
		$lockstatus="unlocked";
	}
	return $lockstatus;
}

function metaname_SaveRegistrarLock($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	if ($params["lockenabled"]) {
		$lockstatus="locked";
	} else {
		$lockstatus="unlocked";
	}
	# Put your code to save the registrar lock here
	# If error, return the error message in the value below
	$values["error"] = $Enom->Values["Err1"];
	return $values;
}

function metaname_GetDNS($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
    # Put your code here to get the current DNS settings - the result should be an array of hostname, record type, and address
    $hostrecords = array();
    $hostrecords[] = array( "hostname" => "ns1", "type" => "A", "address" => "10.0.0.1", );
	return $hostrecords;

}

function metaname_SaveDNS($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
    # Loop through the submitted records
	foreach ($params["dnsrecords"] AS $key=>$values) {
		$hostname = $values["hostname"];
		$type = $values["type"];
		$address = $values["address"];
		# Add your code to update the record here
	}
    # If error, return the error message in the value below
	$values["error"] = $Enom->Values["Err1"];
	return $values;
}

function metaname_RegisterDomain($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	$regperiod = $params["regperiod"];
	$nameserver1 = $params["ns1"];
	$nameserver2 = $params["ns2"];
    $nameserver3 = $params["ns3"];
    $nameserver4 = $params["ns4"];
	# Registrant Details
	$RegistrantFirstName = $params["firstname"];
	$RegistrantLastName = $params["lastname"];
	$RegistrantAddress1 = $params["address1"];
	$RegistrantAddress2 = $params["address2"];
	$RegistrantCity = $params["city"];
	$RegistrantStateProvince = $params["state"];
	$RegistrantPostalCode = $params["postcode"];
	$RegistrantCountry = $params["country"];
	$RegistrantEmailAddress = $params["email"];
	$RegistrantPhone = $params["phonenumber"];
	# Admin Details
	$AdminFirstName = $params["adminfirstname"];
	$AdminLastName = $params["adminlastname"];
	$AdminAddress1 = $params["adminaddress1"];
	$AdminAddress2 = $params["adminaddress2"];
	$AdminCity = $params["admincity"];
	$AdminStateProvince = $params["adminstate"];
	$AdminPostalCode = $params["adminpostcode"];
	$AdminCountry = $params["admincountry"];
	$AdminEmailAddress = $params["adminemail"];
	$AdminPhone = $params["adminphonenumber"];
	# Put your code to register domain here
	$api->register_domain_name( $params["AccountRef"], $params["APIKey"], ... );
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_TransferDomain($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	$regperiod = $params["regperiod"];
	$transfersecret = $params["transfersecret"];
	$nameserver1 = $params["ns1"];
	$nameserver2 = $params["ns2"];
	# Registrant Details
	$RegistrantFirstName = $params["firstname"];
	$RegistrantLastName = $params["lastname"];
	$RegistrantAddress1 = $params["address1"];
	$RegistrantAddress2 = $params["address2"];
	$RegistrantCity = $params["city"];
	$RegistrantStateProvince = $params["state"];
	$RegistrantPostalCode = $params["postcode"];
	$RegistrantCountry = $params["country"];
	$RegistrantEmailAddress = $params["email"];
	$RegistrantPhone = $params["phonenumber"];
	# Admin Details
	$AdminFirstName = $params["adminfirstname"];
	$AdminLastName = $params["adminlastname"];
	$AdminAddress1 = $params["adminaddress1"];
	$AdminAddress2 = $params["adminaddress2"];
	$AdminCity = $params["admincity"];
	$AdminStateProvince = $params["adminstate"];
	$AdminPostalCode = $params["adminpostcode"];
	$AdminCountry = $params["admincountry"];
	$AdminEmailAddress = $params["adminemail"];
	$AdminPhone = $params["adminphonenumber"];
	# Put your code to transfer domain here
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_RenewDomain($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	$regperiod = $params["regperiod"];
	# Put your code to renew domain here
	$api->renew_domain_name( $params["AccountRef"], $params["APIKey"], ( $sld.$tld ), ( ( substr( $tld, -2 ) === 'nz' ) ? $regperiod : ( $regperiod * 12 ) ) );
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_GetContactDetails($params) {
	$tld = $params["tld"];
	$sld = $params["sld"];
	# Put your code to get WHOIS data here
	# Data should be returned in an array as follows
	$values["Registrant"]["First Name"] = $firstname;
	$values["Registrant"]["Last Name"] = $lastname;
	$values["Admin"]["First Name"] = $adminfirstname;
	$values["Admin"]["Last Name"] = $adminlastname;
	$values["Tech"]["First Name"] = $techfirstname;
	$values["Tech"]["Last Name"] = $techlastname;
	return $values;
}

function metaname_SaveContactDetails($params) {
	$key = $params["APIKey"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
	# Data is returned as specified in the GetContactDetails() function
	$firstname = $params["contactdetails"]["Registrant"]["First Name"];
	$lastname = $params["contactdetails"]["Registrant"]["Last Name"];
	$adminfirstname = $params["contactdetails"]["Admin"]["First Name"];
	$adminlastname = $params["contactdetails"]["Admin"]["Last Name"];
	$techfirstname = $params["contactdetails"]["Tech"]["First Name"];
	$techlastname = $params["contactdetails"]["Tech"]["Last Name"];
	# Put your code to save new WHOIS data here
	# If error, return the error message in the value below
	$values["error"] = $error;
	return $values;
}

function metaname_GetEPPCode($params) {
    $key = $params["APIKey"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    # Put your code to request the EPP code here - if the API returns it, pass back as below - otherwise return no value and it will assume code is emailed
    $values["eppcode"] = $eppcode;
    # If error, return the error message in the value below
    $values["error"] = $error;
    return $values;
}

function metaname_RegisterNameserver($params) {
    $key = $params["APIKey"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    $nameserver = $params["nameserver"];
    $ipaddress = $params["ipaddress"];
    # Put your code to register the nameserver here
    # If error, return the error message in the value below
    $values["error"] = $error;
    return $values;
}

function metaname_ModifyNameserver($params) {
    $key = $params["APIKey"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    $nameserver = $params["nameserver"];
    $currentipaddress = $params["currentipaddress"];
    $newipaddress = $params["newipaddress"];
    # Put your code to update the nameserver here
    # If error, return the error message in the value below
    $values["error"] = $error;
    return $values;
}

function metaname_DeleteNameserver($params) {
    $key = $params["APIKey"];
	$testmode = $params["TestMode"];
	$tld = $params["tld"];
	$sld = $params["sld"];
    $nameserver = $params["nameserver"];
    # Put your code to delete the nameserver here
    # If error, return the error message in the value below
    $values["error"] = $error;
    return $values;
}

?>