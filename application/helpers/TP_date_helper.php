<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 擴充 CI date 相關功能的 helper
 *
 *
 * @package		twplayer
 * @author		gary_liu
 * @since		2012/02/14
 * @copyright	Copyright (c) 2012, TWPLAYER Co., Ltd.
 * @link		http://twplayer.com
 */

// 傳回目前的時間
// @return string
function now_s() {
	date_default_timezone_set("Asia/Taipei");
	return date('Y/m/d H:i:s');
}

function today() {
	date_default_timezone_set("Asia/Taipei");
	return date('Y/m/d');
}
