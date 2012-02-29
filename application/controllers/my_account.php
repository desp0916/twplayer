<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 我的帳號
 *
 * 此  controller 是「我的帳號」下所有 controller 的母類別
 *
 * @package		twplayer
 * @author		gary_liu
 * @since		2012/02/14
 * @copyright	Copyright (c) 2012, TWPLAYER Co., Ltd.
 * @link		http://twplayer.com
 */

/* $Id$ */

class My_account extends CI_Controller {

	public $page_title;			// 頁面標題
	public $bread_crumbs;		// 麵包屑

	protected $user;			// 目前使用者
	protected $quiet;			// 錯誤訊息開關（設為 true，即強制關閉）
	protected $class_name;		// 本 controller 名稱
	protected $method;			// 目前執行的 method
	protected $routine;			// 目前執行的 routine
	protected $url_extra;		// 額外的 url
	protected $routines;		// 某 method 具備的 routines
	protected $data;			// 準備顯示於 view 上的資料（含 template, bread crumbs）

	protected $menu_id = 0;

	public function __construct() {
		parent::__construct();
// 		$this->load->helper('jquery');
// 		$this->lang->load('my_account', 'traditional-chinese');

		$this->page_title = '';
		$this->bread_crumbs = array();
		$this->user = null;
		$this->quiet = false;
		$this->class_name = strtolower(get_class($this));
		$this->method = '';
		$this->routine = '';
		$this->url_extra = '';
		$this->routines = array();
		$this->data = array();
		$this->_init();
	}

	/**
	 * 初始化：
	 * 取得 user 的 session，並設定麵包屑
	 */
	protected function _init() {
		$this->user = Apps::get_current_user();
		// FIXME:以後可將下列這一段打開，並配合 filters 與修改所有繼承此 class 的程式（2008/11/04 gary_liu）
//		if (!$this->user) {
//			$this->_show_response('error', '請先登入會員', '/login');
//		}
		$this->bread_crumbs[] = array($this->lang->line('my_account'), '/my_account');
	}

	/**
	 * 初始化一些 view 上面常用的變數：
	 * @param array $data
	 */
	protected function _init_data(&$data = array()) {
		if ($data == "") {
			$data = array (
				'static_host' => $this->config->item('static_host'),
				'site_title' => $this->config->item('site_title'),
				'current_controller' => $this->class_name,
				'current_method' => $this->method,
				'current_routine' => $this->routine,
				'form_name' => $this->class_name . '_' . $this->method . '_' . $this->routine,
				'form_id' => $this->class_name . '_' . $this->method . '_' . $this->routine,
				'form_action' => 'index.php?c=' . $this->class_name . '&m=' . $this->method . '&r=' . $this->routine,
				'error_message' => '',
			);
		} else {
			$data += array(
				'static_host' => $this->config->item('static_host'),
				'site_title' => $this->config->item('site_title'),
				'current_controller' => $this->class_name,
				'current_method' => $this->method,
				'current_routine' => $this->routine,
				'form_name' => $this->class_name . '_' . $this->method . '_' . $this->routine,
				'form_id' => $this->class_name . '_' . $this->method . '_' . $this->routine,
				'form_action' => 'index.php?c=' . $this->class_name . '&m=' . $this->method . '&r=' . $this->routine,
				'error_message' => '',
				);
			}
	 }

	 /**
	 * 登入後重新導向
	 */
	protected function _login_redirect() {
	 	$after_login = $this->session->userdata('after_login');
	 	$this->session->unset_userdata('after_login');
	 	if (empty($after_login)) {
	 		redirect('/my_account/');
	 	} else {
	 		redirect($after_login);
	 	}
	 }

	/**
	 * 「我的帳號」首頁：
	 */
	public function index() {

		if (!Apps::get_current_user_id()) {
			redirect('/login');
			return;
		}

		self::_init_data($data);
		$data['page_title'] = '會員專區';

		$this->load->helper('form');
		$this->load->model(array('user_model'));
		$user_profile = $this->user_model->find_by_id($this->user->id);

		$data['user'] = $this->user;
		$data['user_profile'] = $user_profile;

		$this->_display($data, '', 'my_account');
	}

	/**
	 * 顯示訊息：
	 * 自動依據 $this->request->is_ajax()	來判斷是要用 json_encode 還是 FlashNotice
	 * @param string $response_type	回應訊息類型
	 * @param string $response_msg	回應訊息
	 * @param string $redirect_url	重導網址
	 * @param string $result		其他訊息
	 * @param boolean $exit			是否終止
	 */
	protected function _show_response($response_type = 'success', $response_msg = '', $redirect_url = '', $result = '', $exit = TRUE) {
		if ($this->quiet) { return; }
		if ($this->request->is_ajax()) {
			// header('Content-Type: text/html; charset=utf-8');
			header('Cache-Control: no-cache');
			header('Pragma: nocache');
			echo json_encode(array('response_type' => $response_type, 'message' => $response_msg, 'result' => $result));
			if ($exit) exit;
		} else {
			// 當表單中有<input type = 'file' ... /> 這類欄位，並有加上
			// 「<input type="hidden" name="mimetype" value="json">」時，就會跑到這裡來。
			// header('Content-Type: text/html; charset=utf-8');
			header('Cache-Control: no-cache');
			header('Pragma: nocache');
			$type = $this->input->post('mimetype');
		    if ($type == 'json') {
				echo json_encode(array('response_type' => $response_type, 'message' => $response_msg, 'result' => $result));
				if ($exit) exit;
			} else {
				FlashNotice::add($response_msg, $response_type);
				if (strlen($redirect_url) > 0) {
					redirect($redirect_url);
				} else {
					if ($this->input->server('HTTP_REFERER') && strlen($this->input->server('HTTP_REFERER')) > 0) {
						header('Location:' . $this->input->server('HTTP_REFERER'));
					}
					if ($exit) exit;
				}
			}
		}
	}

	/**
	 * 顯示整個頁面
	 * 未來有新的「版型（layout）」時，只要修改以下程式即可：
	 * @param array $data			頁面資料
	 * @param mixed $page			頁面
	 * @param string $layout		版型
	 */
	protected function _display(&$data, $page = '', $layout = 'common') {

		switch ($layout) {
			case 'ajax':
				$data['_header'] = APPPATH . 'views' . DS . 'common' . DS . '_empty' . EXT;
				$data['_footer'] = APPPATH . 'views' . DS . 'common' . DS . '_empty' . EXT;
				break;
			case 'iframe':
				$data['_header'] = APPPATH . 'views' . DS . 'common' . DS . '_empty' . EXT;
				$data['_footer'] = APPPATH . 'views' . DS . 'common' . DS . '_empty' . EXT;
				break;
			case 'my_account':		// 兩欄式，適用於 「我的帳號」下各子功能
				$data['_header'] = APPPATH . 'views' . DS . 'my_account' . DS . '_header' . EXT;
				$data['_navbar'] = APPPATH . 'views' . DS . 'common' . DS . '_navbar' . EXT;
				$data['_menu'] = APPPATH . 'views' . DS . 'my_account' . DS . '_menu' . EXT;
				$data['_footer'] = APPPATH . 'views' . DS . 'my_account' . DS . '_footer' . EXT;
				break;
			case 'common':			// 滿版（單欄式），for「我的帳號」首頁，使用此版型，需在 view 中自行定義 bread_crumbs
			default:
				$data['_header'] = APPPATH . 'views' . DS . 'common' . DS . '_header' . EXT;
				$data['_navbar'] = APPPATH . 'views' . DS . 'common' . DS . '_navbar' . EXT;
				$data['_footer'] = APPPATH . 'views' . DS . 'common' . DS . '_footer' . EXT;
		}

		if (strlen($page) == 0) {
			$page = Apps::get_route_class() . '/' . Apps::get_route_method();
		}

		if (is_array($this->bread_crumbs) && count($this->bread_crumbs) > 0) {
			$data['bread_crumbs'] = $this->bread_crumbs;
		}

		$this->load->view($page, $data);
	}
}

?>