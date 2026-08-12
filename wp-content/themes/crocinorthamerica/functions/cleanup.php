<?php
/*
 * Cleanup
 */

// Show less info to users on failed login for security.
// (Will not let a valid username be known.)

if (! function_exists('show_less_login_info')) {
    function show_less_login_info()
    {
        return "<strong>ERROR</strong>: Stop guessing!";
    }
}
//add_filter('login_errors', 'show_less_login_info');

// Remove WordPress Version Mention
function otm_remove_version()
{
    return '';
}
add_filter('the_generator', 'otm_remove_version');

// Remove Query Strings From Static Resources

if (! function_exists('otm_theme_remove_script_version')) {
    function otm_theme_remove_script_version($src)
    {
        $parts = explode('?', $src);

        return $parts[0];
    }
}
add_filter('script_loader_src', 'otm_theme_remove_script_version', 15, 1);
add_filter('style_loader_src', 'otm_theme_remove_script_version', 15, 1);
