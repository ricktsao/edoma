<?php
define('BASE_DN', 'baseDN');
define('HOST', 'host');
define('PORT', 'port');
define('SSL', 'ssl');
define('SSL_PORT', 'ssl_port');
define('SSL_CERT', 'ssl_cert');
define('ADMIN_USER', 'admin_user');
define('ADMIN_PASSWORD', 'admin_password');
define('USER_MAPPING_ARR', 'user_mapping');
define('USERID_ATT', 'userid_attribute');
define('LASTNAME_ATT', 'lastname_attribute');
define('FIRSTNAME_ATT', 'firstname_attribute');
define('DISPLAYNAME_ATT', 'displayname_attribute');
define('EMAIL_ATT', 'email_attribute');
define('DEPARTMENT_ATT', 'department_attribute');
define('USER_GROUP_ATT', 'user_group_attribute');
define('USER_GROUP_MAPPING', 'user_group_mapping');
define('GROUP_MAPPING', 'group_mapping');
define('DEPARTMENT_WHITELIST_ARR', 'department_whitelist');
define('GROUP_WHITELIST_ARR', 'group_whitelist');

/**
* Active Directory LDAP Authentication class.
*/

define('ACTIVE_DIRECTORY', 'activedirectory');
define('TYPE','type');
define('RET_FIRSTNAME', 'firstname');
define('RET_LASTNAME', 'lastname');
define('RET_DISPLAYNAME', 'displayname');
define('RET_EMAIL', 'email');
define('RET_DEPARTMENT', 'department');
define('RET_USERID', 'userid');
define('RET_MESSAGE', 'message');
define('RET_STATUS', 'status');
define('RET_ERROR', -1);
define('RET_OK', 0);

class authentication_adLDAP {
public static $arr=array();
function __construct() {
$this->ci =& get_instance();
}
function setting() {
/*$this->ci->load->driver('cache');
if($this->ci->cache->file->get('authentication')){
$parse_data = $this->ci->cache->file->get('authentication');
} else {*/
$parse_data = parse_ini_file("authentication.ini");
/*$this->ci->cache->file->save('authentication',$parse_data,864000);
}*/
$arr= $parse_data;
return $arr;
}
}
?>