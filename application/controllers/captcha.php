<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 驗證碼
 *
 * 將專案 [sms4u] 中所使用的 Library 整合至 CI
 *
 * 備註： 1. 必需整合至 CI 的 rules. 回頭繼續研究相關的套件
 * 			Captcha simple maths for HMVC 4.1.x - http://codeigniter.com/wiki/Captcha_simple_maths_for_HMVC_4.1.x/
 * 			ReCAPTCHA - http://codeigniter.com/wiki/ReCAPTCHA/
 * 		  2.目前將字型放置再根目錄下，並修改 .htaccess。目前的 libraries 下的 font 沒用到.
 *			未來應使用相對路徑把字型設定應該放置的目錄下
 *          for example : require_once( dirname(__FILE__) . '/includes/config.php');
 *		  3.嘗試不同的 TrueType 字型
 *			http://dev.w3.org/cvsweb/Amaya/fonts/#dirlist
 *
 * @package		MyGo
 * @author		kooala
 * @since		2008/7/17
 * @copyright	Copyright (c) 2008, IX Developement Co., Ltd.
 * @link		http://mygo.com
 */

/* $Id: Captcha.php 5465 2009-03-30 05:19:07Z DVP_6 $ */

class Captcha extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) {
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function index($height=40, $width=100) {
		$characters = $this->input->get('characters') ? $this->input->get('characters') : 5;
		$code = $this->generateCode($characters);

		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for ( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$fontpath = $this->config->item('captcha_font_path');
		$textbox = imagettfbbox($font_size, 0, $fontpath, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $fontpath, $code) or die('Error in imagettftext function');

		// Review: 不知為何需要把 session 設定寫在 header 之前 or 會導致 session 抓取兩次
		$this->session->set_userdata('security_code', $code);

		/* output captcha image to browser */
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header('Content-Type: image/jpeg');

		imagejpeg($image);
		imagedestroy($image);
	}

}