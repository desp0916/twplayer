<?php
/**
 *
 * 使用者
 *
 * @author gary
 * @since 2012/02/18
 */

/* $Id$ */

class User_model extends CI_Model {

	function user_model() {
		parent::__construct();
	}

	/**
	 * 新增使用者
	 * @param object $user	使用者詳細資料
	 */
	function add() {
		$this->db->insert('bas_users', $this);
	}

	/**
	 * 更新使用者詳細資料
	 * @param object $user	使用者詳細資料
	 */
	function update($user) {
		$user->updated_date = now_s();
		$this->db->where('id', $user->id);
		$this->db->update('bas_users', $user);
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}

	/**
	 * 依據 ID 取得使用者詳細資料
	 * @param string $id	ID
	 * @return object		使用者詳細資料
	 */
	function find_by_id($id) {
		$query = $this->db->query('SELECT * FROM bas_users WHERE id = ?', array($id));
		return $query->row();
	}

	/**
	 * 依據帳號取得使用者詳細資料
	 * @param string $name	帳號
	 * @return object		使用者詳細資料
	 */
	function find_by_name($name) {
		$query = $this->db->query('SELECT * FROM bas_users WHERE name = ?', array($name));
		$this->firephp->fb($this->db->last_query());
		return $query->row();
	}

	function find_by_name_password($name, $password) {
		if (empty($name) || empty($password)) return null;
		// NOTE: 因為兩次 encode 產生的字串不相同. 只好 select 出來後再比.
		$query = $this->db->get_where('bas_users', array('name' => $name));
		$user = $query->row();
		$this->firephp->fb($this->db->last_query());

		if ($user) {
			$this->load->library('encrypt');
			if ($password != $this->encrypt->decode($user->password)) {
				$user = null;
			}
		}
		return $user;
	}

	function find_by_email($email) {
		if (empty($email)) return null;
		$query = $this->db->get_where('bas_users', array('email' => $email));
		return $query->row();
	}

	function do_login($name, $password) {
		if (empty($name) || empty($password)) return null;
		$login_user = $this->find_by_name_password($name, $password);

		if ($login_user) {
			$this->load->helper('date_helper');
			$data = array(
		        		'last_login_date' => now_s(),
		            	'last_login_ip' => $_SERVER["REMOTE_ADDR"],
						'session_key' => $this->session->userdata('session_id')
			);
			$this->db->where('id', $login_user->id);
			$this->db->update('bas_users', $data);
		}
		return $login_user;
	}

	function do_logout($user_id) {
		$this->db->update('bas_users', array('session_key' => ''), array('id' => $user_id));
		return true;
	}

}

?>