<?php
include(APPPATH.'libraries/authentication_adLDAP.php');

class authentication {
function __construct() {
$this->ci =& get_instance();
}


/**
* This is a generic function that can be used to test connection to any repository. The argument should
* have a field called ��type�� which would have a value like ��activedirectory�� to specify which type of repository that needs to be
* connected. Based on each repository the $connection_settings array would have different fields. For example for AD-LDAP
* fields would be type, baseDN, host, port, ssl, ssl_port, ssl_cert, admin_user, admin_password, user_mapping, userid_attribute,
* lastname_attribute, firstname_attribute, displayname_attribute, email_attribute, department_attribute, user_group_attribute,
* group_mapping, department_whitelist, group_whitelist. This API can also be used to test the ��mapping��
* information configurations like ��where to get the user department in LDAP��.
* @connection_setting Array of all the connection information needed to establish an administrative connection to a repository
* @return TRUE if everything is successful
* Error message if connection is not successful
*/
function get_setting() {
	$ldap_config= new authentication_adLDAP();
	$parse_data=$ldap_config->setting();
	return $parse_data;
}
/**
* function aa_user_authenticate
* Given a username and password, this API would connect to the appropriate backend repository and performs the
* authentication and returns the user object if successful. If not successful returns an error message.
* @username Username of the end user
* @password password of the end user
* @return $obj if successful that includes the firstname, lastname, displayname, emailid of the user and status as 0.
* $obj with status as -1 and the appropriate Error message
*/

public function authenticate($username, $password, $preventRebind = false) {

if($username=='' || $password=='') {
	$return_obj[RET_STATUS] = RET_ERROR;

	$validation_message='';
	if($username=='') {
	$validation_message .= 'Username is required.';
	} if($password=='') {
	$validation_message .= 'Password is required.';
	}
	$return_obj[RET_MESSAGE] = $validation_message;
	return $return_obj;
}

$conn=$this->get_setting();
$ldap_host= $conn['host'];

$ldap_basedn = $conn[BASE_DN];
$ldap_dn = $conn[USERID_ATT] .'=' . strtolower($username) . ',' . $conn[USER_MAPPING_ARR] . ',' . $ldap_basedn;

try {

	if (!function_exists('ldap_connect')) 
	{
		$return_obj[RET_STATUS] = RET_ERROR;
		$return_obj[RET_MESSAGE] = 'LDAP functionality not present. Either load the module ldap php module or use a php with ldap support compiled in.';
		log_message('error', 'LDAP functionality not present');
		syslog(LOG_ERR, 'LDAP functionality not present');
		return $return_obj;
	}
// Connect to the AD/LDAP server
$ldapConnection = ldap_connect($ldap_host);

if ($ldapConnection){
echo "<br>Initializes to connecting LDAP Server";
	
// Set some ldap options for talking to AD
ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
// Bind as the user
$this->ldapBind = @ldap_bind($ldapConnection, $ldap_dn, $password);
if ($this->ldapBind) {
log_message('LOG_INFO', 'User Authenticated…');
syslog(LOG_INFO, 'User Authenticated…');
$filter = "(".$conn[USERID_ATT]."=". $username . ")";
// search active directory
if (!($search = ldap_search($ldapConnection, $ldap_dn, $filter))) {
$return_obj[RET_STATUS] = RET_ERROR;
$return_obj[RET_MESSAGE] = "Error in search query for user :: " . $username;
log_message( 'error',  "Error in search query for user ::" . $username);
syslog(LOG_ERR, "Error in search query for user ::" . $username);
return $return_obj;
}


$number_returned = ldap_count_entries($ldapConnection, $search);
$info = ldap_get_entries($ldapConnection, $search);
log_message('info', "The number of entries returned is" . $number_returned);
syslog(LOG_INFO, "The number of entries returned is" . $number_returned);
$user_obj = array();
if ($number_returned > 0) {
for ($i = 0; $i < $info["count"]; $i++) {
$user_obj[RET_FIRSTNAME] = $info[$i][strtolower($conn[FIRSTNAME_ATT])][0];
$user_obj[RET_LASTNAME] = $info[$i][strtolower($conn[LASTNAME_ATT])][0];
$user_obj[RET_DISPLAYNAME] = $info[$i][strtolower($conn[DISPLAYNAME_ATT])][0];
$user_obj[RET_EMAIL] = $info[$i][strtolower($conn[EMAIL_ATT])][0];
$user_obj[RET_DEPARTMENT] = $info[$i][strtolower($conn[DEPARTMENT_ATT])][0];
$user_obj[RET_USERID] = $info[$i][strtolower($conn[USERID_ATT])][0];
$user_obj[RET_STATUS] = RET_OK;
log_message('error', "Successful login:". $info[$i][strtolower($conn[USERID_ATT])][0] . "(" . $username . ") from" . $this->ci->input->ip_address() . "at" . date('d-m-y H:i:s'));
ldap_unbind($ldapConnection);
return $user_obj;
}

}
else {
	$return_obj[RET_STATUS] = RET_ERROR;
	$return_obj[RET_MESSAGE] = "Could not get user";
	log_message('error', "No User found : Username :" . $username . " from " . $this->ci->input->ip_address() . " at " . date('d-m-y H:i:s'));
	ldap_unbind($ldapConnection);
	return $return_obj;
}
return true;
}
else {
$return_obj[RET_STATUS] = RET_ERROR;
$return_obj[RET_MESSAGE] = "Invalid credentials.(username or password is wrong.)";
log_message('error', "Failed login attempt by : Username :" . $username . " from " . $this->ci->input->ip_address() . " at " . date('d-m-y H:i:s'));
ldap_unbind($ldapConnection);
return $return_obj;
}
}
} catch (adLDAPException $e) {
$return_obj[RET_STATUS] = RET_ERROR;
$return_obj[RET_MESSAGE] = $e;
log_message('error', $e);
syslog(LOG_ERR, $e);
return $return_obj;
}
}
}
?>