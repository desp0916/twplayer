<div class="navbar">
  <div style="float:left;"><?php echo $site_title ?></div>
  <div style="float:right;">
<?php if (!Apps::get_current_user_id()) :?>
  <a href="<?php echo site_url('/login')?>">登入</a>
<?php else: ?>
  <a href="<?php echo site_url('/logout')?>">登出</a>
<?php endif ?>
  </div>
</div>