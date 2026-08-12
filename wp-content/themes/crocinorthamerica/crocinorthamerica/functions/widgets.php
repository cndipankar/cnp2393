<?php
/*
 * Widgets
 */

function otm_theme_widgets_init()
{

  /*
  Blog Sidebar (one widget area)
   */
    register_sidebar([
        'name' => __('Blog Sidebar', 'otm_theme'),
        'id' => 'blog-sidebar',
        'description' => __('Blog Sidebar Widget Area', 'otm_theme'),
        'before_widget' => '<section class="%1$s %2$s mb-2">',
        'after_widget' => '</section>',
        'before_title' => '<h4 class="widget-title mb-1">',
        'after_title' => '</h4>',
    ]);

    /*
    Page Sidebar (one widget area)
     */
    register_sidebar([
        'name' => __('Page Sidebar', 'otm_theme'),
        'id' => 'page-sidebar',
        'description' => __('Page Sidebar Widget Area', 'otm_theme'),
        'before_widget' => '<section class="%1$s %2$s mb-2">',
        'after_widget' => '</section>',
        'before_title' => '<h4 class="widget-title mb-1">',
        'after_title' => '</h4>',
    ]);

    register_sidebar([
        'name' => __('Footer', 'otm_theme'),
        'id' => 'footer-widget-area',
        'description' => __('The footer widget area', 'otm_theme'),
        'before_widget' => '<section class="%1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ]);
}
add_action('widgets_init', 'otm_theme_widgets_init');


/**!
 * OTM Form Widget
 */


// Register and load the widget
function otm_load_widget()
{
    register_widget('otm_widget', 'sidebar-form');
}
add_action('widgets_init', 'otm_load_widget');

// Creating the widget
class otm_widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(

// Base ID of your widget
'otm_widget',

// Widget name will appear in UI
__('OTM Form', 'otm_widget_domain'),

// Widget description
[ 'description' => __('Contact Form Widget', 'otm_widget_domain'), ]
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // This is where you run the code and display the output
        echo __('
<form class="OTMForm vertical-form">
  <div class="input-wrapper mb-1 position-relative">
    <i class="fas fa-user position-absolute text-secondary form-icon" aria-hidden="true"></i>
    <input class="form-control" type="text" name="from" placeholder="Full Name*" required>
  </div><!--/.input-wrapper-->
  <div class="input-wrapper mb-1 position-relative">
    <i class="fas fa-envelope position-absolute text-secondary form-icon" aria-hidden="true"></i>
    <input class="form-control" type="email" name="sender" placeholder="Email Address*" required>
  </div><!--/.input-wrapper-->
  <div class="input-wrapper mb-1 position-relative">
    <i class="fas fa-phone position-absolute text-secondary form-icon" aria-hidden="true"></i>
    <input class="form-control" type="text" name="phone" placeholder="Phone Number*" required>
  </div><!--/.input-wrapper-->
  <div class="input-wrapper">
    <textarea class="form-control mb-1" name="msg"></textarea>
    <input class="d-none" name="your-url" type="text" tabindex="-1" autocomplete="off" />
    <button class="btn btn-secondary btn-block mx-auto" type="submit" title="Click to Submit">Send Message</button>
  </div><!--/.input-wrapper-->
  <div class="alert alert-success mt-2 d-none" role="alert">
    Your Message Has been Successfully Sent. Looking forward to speaking with you soon.
  </div><!--/.alert-success-->
  <div class="alert alert-danger mt-2 d-none" role="alert">
    Your Message Has Not been sent. Try again later.
  </div><!--/.alert-danger-->
</form>
', 'otm_widget_domain');
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance[ 'title' ])) {
            $title = $instance[ 'title' ];
        } else {
            $title = __('Contact Us', 'otm_widget_domain');
        }
        // Widget admin form?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
  <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </p>
  <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }
} // Class otm_widget ends here
