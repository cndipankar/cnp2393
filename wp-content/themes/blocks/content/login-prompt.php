<?php
/**
 *
 * Download Card - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$user = wp_get_current_user();
$allowed_roles = array( 'administrator' );
$h = get_field('heading');
$desc = get_field('description');
?>
<?php if (!is_user_logged_in()): ?>
    <div class="login-container container p-sm-0">
        <div class="login-prompt">
            <h2><?php echo $h; ?></h2>
            <?php if ($desc) : ?>
                <p class="sub"><?php echo $desc; ?></p>
            <?php endif; ?>
            <a href="<?php echo wp_login_url( get_permalink() ); ?>" class="btn1" data-text="Login" title="Login">Login</a>
            <p>Don't have an account? <a href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register here</a>.</p>
        </div>
    </div>
<?php elseif(is_user_logged_in()): ?>
    <?php if (array_intersect( $allowed_roles, $user->roles )): ?>
        <div class="login-container container p-sm-0">
            <div class="login-prompt">
                <h2><?php echo $h; ?></h2>
                <?php if ($desc) : ?>
                    <p class="sub"><?php echo $desc; ?></p>
                <?php endif; ?>
                <a href="<?php echo wp_login_url( get_permalink() ); ?>" class="btn1" data-text="Login" title="Login">Login</a>
                <p>Don't have an account? <a href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register here</a>.</p>
            </div>
        </div>
    <?php endif; ?>
<?php else: // Do nothing
endif; ?>