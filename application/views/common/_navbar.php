<div class="navbar">
  <div style="float:left;"><a href="<?php echo site_url('/'); ?>"><?php echo $site_title ?></a></div>
  <div style="float:right;">
  <input type="text" name="q" value="" size="30" />
<?php if (!Apps::get_current_user_id()) :?>
  <a href="<?php echo site_url('/login')?>">登入</a>
<?php else: ?>
  <a href="<?php echo site_url('/logout')?>">登出</a> |
  <a href="<?php echo site_url('/my_account')?>">會員專區</a>
<?php endif ?>
  </div>
  <div class="clear"></div>
</div>