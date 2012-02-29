<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 會員控制項
*
* @author gary
* @since 2012/02/18
*
*/

/* $Id$ */

require_once('my_account' . EXT);

class Account extends My_account {

	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		$this->load->helper(array('form', 'jquery'));
		$this->load->library(array('form_validation', 'encrypt'));
		$this->lang->load('user');
	}

	/**
	 * 註冊
	 */
	public function signup() {
		if (Apps::get_current_user_id()) {
			redirect('/my_account/');
			return;
		}
		parent::_init_data($data);
		$data['page_title'] = '加入會員';
		$this->_display($data);
	}

	/**
	 * 登入
	 */
	public function login() {

		if (Apps::get_current_user_id()) {
			$this->_login_redirect();
			return;
		}

		$data = array();
		parent::_init_data($data);
		$data['page_title'] = '登入';

		if (isset($_POST) && is_array($_POST) && !empty($_POST)) {

			// @TODO: 將驗證碼() 整合至規則中
			if ($this->input->post('security_code') != $this->session->userdata('security_code') ) {
				$data['error_message'] = '驗證碼錯誤';
				$this->_display($data);
				return;
			}

			$this->form_validation->set_rules('username', '帳號', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', '密碼', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember_me', '在這一台電腦記得我', 'trim|xss_clean');

			if ($this->form_validation->run() == TRUE) {

				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$remember_me = $this->input->post('remember_me');

				$login_user = $this->user_model->do_login($username, $password);

				if ($remember_me == 'yes') {
					$this->_set_autologin_cookies($username, $password);
				} else {
					$this->_clear_login_cookies();
				}

				if (!empty($login_user)) {
					// Now we don't need this.
					$this->session->unset_userdata('security_code');
					$this->_set_user_session($login_user);
					$this->_login_redirect();
				}

				$data['error_message'] = '帳號或密碼錯誤';
			}
		}

 		$this->_display($data);
	}

	/**
	 * 登入 ajax 版
	 */
	public function ajax_login() {
	}

	/**
	 * 設定登入欄位（帳號、密碼、在這台電腦記得我）驗證規則：
	 */
	function _set_login_validation() {
		$this->form_validation->set_rules('username', '帳號', 'trim|required|min_length[3]|max_length[20]|alpha_dash|xss_clean');
		$this->form_validation->set_rules('password', '密碼', 'trim|required|min_length[4]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('remember_me', '在這一台電腦記得我', 'trim|min_length[1]|max_length[5]|xss_clean');
	}

	/**
	 * 自動登入，儲存帳號與密碼於 cookies
	 *
	 * @param string $name			帳號
	 * @param string $password		密碼
	 * @return void
	 */
	function _set_autologin_cookies($name, $password) {
		$this->load->helper('cookie');
		$cookie = array(
	                   'name'   => 'user_name',
	                   'value'  => $name,
	                   'expire' => '31536000',
	                   'domain' => $this->config->item('cookie_domain'),
		);
		set_cookie($cookie);
		$password_cookie = array(
	                   'name'   => 'user_password',
	                   'value'  => $this->encrypt->encode($password),
	                   'expire' => '31536000',
	                   'domain' => $this->config->item('cookie_domain'),
		);
		set_cookie($password_cookie);
		return;
	}

	/**
	 * 清除自動登入的 cookies
	 *
	 */
	function _clear_login_cookies() {
		$this->load->helper('cookie');
		delete_cookie('user_name', $this->config->item('cookie_domain'));
		delete_cookie('user_password', $this->config->item('cookie_domain'));
	}

	/**
	 * 登出
	 */
	function logout() {
		$user = Apps::get_current_user();
		if ($user) {
			$this->user_model->do_logout($user->id);
		}
		$this->_unset_user_session();
		$this->_clear_login_cookies();
		redirect();
	}

	/**
	 * 設定使用者 session
	 * @param object $user	使用者
	 */
	function _set_user_session($user) {
		if($user) {

			Apps::set_current_user($user);

			// 讀取 user profile
// 			$this->load->model('user_profile_model');
// 			$user_profile = $this->user_profile_model->find_by_user_id($user->id);
			//			if($user_profile) {
			//				Apps::set_current_user_profile($user_profile);
			//			}

			// 讀取 user roles
// 			$roles = UserRole::get_user_roles($user->id);
// 			Apps::set_current_user_roles($roles);
		}
	}

	function _unset_user_session() {
		Apps::reset_current_user();
	}

	/**
	 * 忘記密碼（輸入帳號取回）
	 */
	function forgot_password() {
		if (Apps::get_current_user()) {
			FlashNotice::add('請先登出系統', 'warning');
			redirect();
			return;
		}
		$data['page_title'] = '重新索取密碼（輸入帳號取回）';
		$this->index();
	}

	/**
	 * 忘記 E-mail（輸入密碼取回）
	 */
	function forgot_email() {
		if (Apps::get_current_user()) {
			FlashNotice::add('請先登出系統', 'warning');
			redirect();
			return;
		}
		$data['page_title'] = '重新索取 E-mail（輸入密碼取回）';
		$this->index();
	}

}