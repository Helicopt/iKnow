<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 function enum( /*...*/ &$itms) {

	foreach( $itms as $name => $enum )
		define($name, $enum);

}


/*example:
$enum_Array = Array(
'REGISTERED_USER' => 1,
'EMAIL_VERIFIED_USER' => 1 << 1,
'INFO_INCOMPLETE_USER' => 1 << 2,
'DENIED_USER' => 1 << 3,
'NORMAL_USER' => 1 << 4,
'SUPER_ADMIN' => 1 << 5,
'VIP_USER' => 1 << 6,
);
enum($enum_Array);
unset($enum_Array);
/*

/* End of file enum.php */
/* Location: ./application/helpers/enum.php */