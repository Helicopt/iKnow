<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	var $auth = 0;
	var $user_info = array();

    public function __construct()
    {
        parent::__construct();

$enum_array = array(
'EMAIL_VERIFIED_USER' => 1 << 1, //已经通过Email验证的注册用户（用户数据表里面status项）
'INSPECT_USER' => 1 << 2, //有审核举报权限的用户
'SUPER_ADMIN' => 1 << 5, //拥有全局权限的用户

'SUCCESS_MSG' => 0,
'FAIL_MSG' => 1,
'AUTH_MSG' => 2,
'UNKNOWN_MSG' => -1

);

enum($enum_array);
unset($enum_array);

		session_start();

		if ($this->userm->isLogin()) {
			$this->user_info = $this->userm->get_current_user_info();
			$this->auth = $this->user_info['id'];
		}
		else $this->auth = 0;

		if (!$this->userm->isLogin() &&
				($this->uri->segment(1) != 'explore') &&
				($this->uri->segment(1) != 'register') &&
				(current_url() != base_url().index_page()) &&
				($this->uri->segment(1) != 'help') &&
				($this->uri->segment(1) != 'error')
				){
			redirect(base_url());
		}
    }

}

?>