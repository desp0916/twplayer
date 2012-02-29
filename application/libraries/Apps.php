<?php
/**
 * 定義系統的商業邏輯
 *
 * TODO: 重構 login_user, 應該全部改為 current_user
 *
 * @package		twplayer
 * @author		gary_liu
 * @since		2012/02/14
 * @copyright	Copyright (c) 2012, TWPLAYER Co., Ltd.
 * @link		http://twplayer.com
 */

/* $Id */

class Apps {

	/**
	 * 產生 permalink（唯一值，可用於 DB 中的 PK，並暴露於系統外部）
	 * @return string		permalink
	 */
	static function get_permalink()	{
		return uniqid();
	}

	/**
	 * 自 session 中取得目前登入使用者的基本資料
	 * @return object		登入使用者的基本資料
	 */
	static function get_current_user() {
		$CI =& get_instance();
		return $CI->session->userdata('login_user');
	}

	/**
	 * 從 session 來判斷是目前登入使用者的帳號是否為 XXX?
	 * @param string $user_name		欲判斷的帳號
	 * @return boolean				是或否
	 */
	static function is_current_user($user_name)	{
		return (Apps::get_current_user() &&
		(Apps::get_current_user()->name === $user_name xor Apps::get_current_user()->id === $user_name)) ? True : False;
	}

	/**
	 * 從 session 來判斷，目前登入使用者的 User ID 為 XXX?
	 * @param unsigned integer $user_id	欲判斷的 User ID
	 * @return boolean					是或否
	 */
	static function is_current_user_by_user_id($user_id) {
		return (self::get_current_user()
		&& Apps::get_current_user()->id === $user_id) ? True : False;
	}

	/**
	 * 取得目前登入使用者本身的 User ID
	 * @return mixed	User ID 或者 FALSE
	 */
	static function get_current_user_id() {
		$user = self::get_current_user();
		return ($user) ? $user->id : False;
	}

	/**
	 * 儲存使用者資料於 session 中
	 * @param object $user		使用者
	 */
	static function set_current_user($user)	{
		$CI =& get_instance();
		$CI->session->set_userdata('login_user', $user);
	}

	/**
	 * 取得目前登入使用者的帳號
	 * @return string		使用者帳號
	 */
	static function get_current_user_name() {
		if (Apps::get_current_user()) {
			return Apps::get_current_user()->name;
		}
	}

	/**
	 * 取得目前使用者正在執行的 controller （class）名稱
	 * @return string		controller （class）名稱
	 */
	static function get_route_class() {
		$RTR =& load_class('Router');
		return $RTR->fetch_class();
	}

	/**
	 * 取得目前使用者正在執行的 method 名稱
	 * @return string		method 名稱
	 */
	static function get_route_method() {
		$RTR =& load_class('Router');
		return $RTR->fetch_method();
	}

	/**
	 * 自 session 中取得目前使用者角色
	 * @return array		角色
	 */
	static function get_current_user_roles() {
		$CI =& get_instance();
		return $CI->session->userdata('login_user_roles');
	}

	/**
	 * 儲存使用者的角色於 session 中
	 * @param array 		角色
	 */
	static function set_current_user_roles($roles) {
		$CI =& get_instance();
		$CI->session->unset_userdata('login_user_roles');
		$CI->session->set_userdata('login_user_roles', $roles);
	}

	/**
	 * 清空使用者的所有 session 資料（用於登出動作）
	 */
	static function reset_current_user() {
		$CI =& get_instance();
		$CI->session->unset_userdata('login_user');
		$CI->session->unset_userdata('login_user_profile');
		$CI->session->unset_userdata('login_user_roles');
		$CI->session->sess_destroy();
	}

	/**
	 * 儲存使用者 profile 資料於 session 中
	 * @param object 		profile 資料
	 */
	static function set_current_user_profile($user_profile) {
		$CI =& get_instance();
		$CI->session->unset_userdata('login_user_profile');
		$CI->session->set_userdata('login_user_profile', $user_profile);
	}

	/**
	 * 自 session 中取得目前登入使用者的 profile
	 * @return object		profile 資料
	 */
	static function get_current_user_profile() {
		$CI =& get_instance();
		return $CI->session->userdata('login_user_profile');
	}

	static function display_error($error_message = '') {
		if (is_string($error_message) && strlen($error_message) > 0) {
			echo '<div class="error_message"><p>' . $error_message . '</p></div>';
		}
	}

	/**
	 * 初始話圖形驗證碼
	 * @return array	圖形驗證碼
	 */
	static function init_captcha() {
		$CI =& get_instance();
		$CI->load->helper('captcha');
		$CI->load->config('apps');
// 		$CI->load->library('database');
		$vals = array('font_path' => $CI->config->item('captcha_font_path'),
						'img_path' => $CI->config->item('captcha_img_path'),
						'img_url' => 'http://www.twplayer.com/captcha/');
		$cap = create_captcha($vals);
		$v = array('captcha_time' => $cap['time'],
					'ip_address' => $CI->input->ip_address(),
					'word' => $cap['word']);
		$query = $CI->db->insert_string('captcha', $v);
		$CI->db->query($query);
		return $cap;
	}

	static function check_captcha($word) {
		$CI =& get_instance();
		// First, delete old captchas
		$expiration = time() - 7200; // Two hour limit
		$CI->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);
		// Then see if a captcha exists:
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($word, $CI->input->ip_address(), $expiration);
		$query = $CI->db->query($sql, $binds);
		$row = $query->row();

		return ($row->count == 0) ? FALSE : TRUE;
	}
}

class Object {}

?>