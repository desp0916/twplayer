<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * �ڪ��겣���
 *
 * @author gary
 * @since 2012/02/28
 *
*/

/* $Id$ */

require_once('my_account' . EXT);

class My_asset extends My_account {

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
	 * �򥻸�ƺ��@
	 */
	public function index() {
		parent::_init_data($data);

		$data['page_title'] = '�ڪ��겣���@';
		$this->_display($data, '', 'my_account');
	}

	/**
	 * �򥻸�ƺ��@
	 */
	public function update() {

	}

}