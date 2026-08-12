<?php
  $social_profiles = get_option('otm_theme_options')['social-profiles'];
?>
<ul class="social-links list-unstyled list-inline">
<?php if ($social_profiles): ?>
  <?php foreach ($social_profiles as $social_profile): ?>
  <li class="list-inline-item">
    <a class="<?php echo $social_profile['social-profile-icon'] ?>" href="<?php echo $social_profile['social-profile-url'] ?>" target="_blank" title="<?php echo $social_profile['social-profile'] ?>"></a>
  </li>
  <?php endforeach; ?>
<?php endif; ?>
</ul><!--/.social-links-->
