<?php include($_header); ?>

<div id="main_content">
  <div class="bread_crumbs">基本資料維護</div>

    <form name="update_profile" method="post" action="<?php echo site_url('/my_profile'); ?>">
      <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
      <div class="css_table">
      <div class="css_tr"><div class="css_td"><label for="username">帳號：</label></div><div class="css_td"><?php echo $profile->name; ?></div></div>
      <div class="css_tr"><div class="css_td"><label for="email">E-mail：</label></div><div class="css_td"><input type="text" name="email" id="email" value="<?php echo $profile->email; ?>" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="cell">手機：</label></div><div class="css_td"><input type="text" name="cell" id="cell" value="<?php echo $profile->cell; ?>" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="tel1">電話一：</label></div><div class="css_td"><input type="text" name="tel1" id="tel1" value="<?php echo $profile->tel1; ?>" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="tel2">電話二：</label></div><div class="css_td"><input type="text" name="tel2" id="tel2" value="<?php echo $profile->tel2; ?>" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="fax">傳真：</label></div><div class="css_td"><input type="text" name="fax" id="fax" value="<?php echo $profile->fax; ?>" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><label for="password">請輸入密碼：</label></div><div class="css_td"><input type="password" name="password" id="password" size="30" maxlength="20" /></div></div>
      <div class="css_tr"><div class="css_td"><input type="submit" name="submit" value="送出" /></div></div>
    </div>
  </form>

<?php Apps::display_error($error_message); ?>
<?php Apps::display_error(validation_errors('', '')); ?>

  <div class="clear"></div>
</div>

<?php include($_footer); ?>