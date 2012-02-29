<?php include($_header); ?>
<?php include($_navbar); ?>

<div id="main_content">
  <div class="bread_crumbs">登入</div>

    <form name="form_login" id="form_login" method="post" action="<?php echo site_url('/login'); ?>">
      <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
      <div class="css_table">
      <div class="css_tr"><div class="css_td"><label for="username">帳號：</label></div><div class="css_td"><input type="text" id="username" name="username" autocomplete="off" value="" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="password">密碼：</label></div><div class="css_td"><input type="password" id="password" name="password" autocomplete="off" value="" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="security_code">驗證碼：</label></div><div class="css_td"><input type="text" id="security_code" name="security_code" autocomplete="off" value="" /></div></div>
      <div class="css_tr"><div class="css_td">請輸入下圖文字作為驗證碼：<br /><img id="captcha_image" src="<?php echo site_url('/captcha/index/');?>" width="100" height="40" alt="看不清楚嗎？請點擊重新產生驗證碼。" title="看不清楚嗎？請點擊重新產生驗證碼。" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="remember_me"><input type="checkbox" id="remember_me" name="remember_me" value="yes" />在這台電腦記住我</label></div></div>
      <div class="css_tr"><div class="css_td"><input type="submit" value="登入" /></div></div>
      </div>
    </form>

<?php Apps::display_error($error_message); ?>
<?php Apps::display_error(validation_errors('', '')); ?>

<a href="<?php echo site_url('/forgot_password')?>">忘記密碼？</a>
<a href="<?php echo site_url('/signup')?>">加入會員</a>
<?php
// $CI =& get_instance();
// echo $CI->session->userdata('security_code');
?>
</div>

<script type="text/javascript">
<!--
$('#captcha_image').click(function() {
	var t = new Date().getTime();
	var url = '<?php echo site_url('/captcha/index/');?>?t=' + t;
	$(this).attr({src: url});
});

function submitLogin(form_obj) {
	var options = {
	};
}
//-->
</script>
<?php include($_footer); ?>