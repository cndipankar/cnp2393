<?php
/**
 *
 * Two-column Content - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$h = get_field('heading');
$c = get_field('content');
$btn = get_field('btn');
$url = get_field('url');
$align = get_field('alignment');
$rev = get_field('reverse');
$list = get_field('list');
?>

<div class="row tclc <?php echo esc_attr($classes); if ($rev == '1'): echo 'flex-row-reverse '; endif; if($align): echo $align; endif; ?>">
    <div class="col-12 col-lg-5">
        <div class="content <?php if ($rev == '1'): echo 'ml-lg-auto'; endif;?>">
            <?php 
                if($h['tag'] == 'h2'):
                    if($h['heading']): 
                    echo '<h2>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
                    endif;
                elseif($h['tag'] == 'h3'):
                    if($h['heading']): 
                    echo '<h3>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
                    endif;
                endif;
                if ($c):
                    echo $c;
                endif;
                if ($btn):
                    echo '<a class="btn1 small" href="' . $url . '" data-text="' . $btn . '" title="' . $btn . '">' . $btn . '</a>';
                endif;
            ?>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="list-content">
            <?php if ($list): echo $list; endif;?>
        </div>
    </div>
</div>