<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 基本資料控制項
*
* @author gary
* @since 2012/02/18
*
*/

/* $Id$ */

require_once('my_account' . EXT);

class My_profile extends My_account {

	function __construct() {
		parent::__construct();

		if (!Apps::get_current_user_id()) {
			redirect('/login');
			return;
		}

		$this->load->model('user_model');
		$this->load->helper(array('form', 'jquery'));
		$this->load->library(array('form_validation', 'encrypt'));
		$this->lang->load('user');

	}

	/**
	 * 基本資料維護
	 */
	public function index() {

		$data = array();
		parent::_init_data($data);
		$data['page_title'] = '基本資料維護';
		$profile = $this->user_model->find_by_id($this->user->id);

		if (is_object($profile) && !empty($profile)) {
			if (isset($_POST) && is_array($_POST) && !empty($_POST)) {
				$password = $this->input->post('password');
				// @NOTE: 修改基本資料前，務必要求重新輸入密碼，以驗證是否為本人？
				if (strlen($password) > 0 && $password == $this->encrypt->decode($profile->password)) {
					$profile = $this->_update();

					if (is_object($profile) && !empty($profile)) {
						$profile->name = $this->user->name;
						$profile->password = $this->user->password;
						Apps::set_current_user($profile);
						$data['error_message'] = '更新完成';
					} else {
						$data['error_message'] = '更新失敗';
					}

				} else {
					$data['error_message'] = '密碼錯誤';
				}
			}
			$data['profile'] = $profile;
			$this->_display($data, '', 'my_account');
		}

	}

	/**
	 * 基本資料維護
	 * @NOTE: 不要讓 user 在此修改密碼！
	 * @TODO: if user changes his e-mail, then we should resend the validation e-mail.
	 */
	private function _update() {

		$old_email = $this->user->email;

		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|min_length[9]|max_length[255]|valid_email|xss_clean');
		$this->form_validation->set_rules('cell', '手機', 'trim|min_length[4]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('tel1', '電話一', 'trim|min_length[4]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('tel2', '電話二', 'trim|min_length[4]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('fax', '傳真', 'trim|min_length[4]|max_length[20]|xss_clean');

		if ($this->form_validation->run() == TRUE) {

			$new_profile = new stdClass();

			$new_profile->id = $this->user->id;
			$new_profile->email = $this->input->post('email');
			$new_profile->cell = $this->input->post('cell');
			$new_profile->tel1 = $this->input->post('tel1');
			$new_profile->tel2 = $this->input->post('tel2');
			$new_profile->fax = $this->input->post('fax');

			$res = $this->user_model->update($new_profile);

			return ($res) ? $new_profile : FALSE;
		}
		return FALSE;
	}

}