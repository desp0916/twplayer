<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 網站首頁
 *
 * @package		twplayer
 * @author		gary_liu
 * @since		2012/02/14
 * @copyright	Copyright (c) 2012, TWPLAYER Co., Ltd.
 * @link		http://twplayer.com
 */

/* $Id$ */

require_once('my_account' . EXT);

class Home extends My_account {

	function __construct() {
		parent::__construct();
	}

	public function index($page = 'index') {
		parent::_init_data($data);
		$data['page_title'] = $this->config->item('site_title');
		$this->_display($data);
	}

}