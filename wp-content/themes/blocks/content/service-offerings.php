<?php
/**
 *
 * Service Offerings - Block Template.
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
?>

<div class="service-offerings">
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
    ?>
    <?php if (have_rows('service_offerings')):
        $i = 1;
        echo '<div class="offerings">';
        while (have_rows('service_offerings')) : the_row();
            $sl = get_sub_field('sl_no');
            $heading = get_sub_field('heading');
            $desc = get_sub_field('description');
        ?>
        <div class="item">
            <?php if ( $sl ) :  
                echo '<div class="sl">' . $sl . '</div>';
            endif;
            echo '<div class="text">';
            if ( $heading ) :  
                echo '<h4>' . $heading . '</h4>';
            endif;
            if ( $desc ) :  
                echo '<p>' . $desc . '</p>';
            endif;
            echo '</div>';
            ?>
        </div>
        
    <?php $i++;
        endwhile;
        echo '</div>';
    else :
        echo '<p>Please add service offerings.</p>';
    endif; ?>
</div>