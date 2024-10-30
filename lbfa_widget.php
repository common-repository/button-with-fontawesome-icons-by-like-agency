<?php
/*
Plugin Name:  Button with FontAwesome icons by LIKE.agency
Plugin URI:   
Description:  This plugin allows you to add a widget with custom text, link, FontAwesome icon and a custom button. <strong>In order for this plugin to work, please make sure you use FontAwesome toolkit in your theme.</strong> If not, follow <a href="http://fontawesome.io/" target="_blank" rel="nofollow">this link</a> to get it. Have fun!
Version:      1.0
Author:       LIKE.agency
Author URI:   https://like.agency/
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  lbfa
Domain Path:  /lang

{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
*/

// scripts and styles

// admin
function lbfa_enqueue_admin_scripts() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style('lbfa_css', plugins_url('/inc/lbfa_admin.css', __FILE__));
}

// frontend
function lbfa_enqueue_front_css(){
    wp_enqueue_style('lbfa_css', plugins_url('/inc/lbfa_front.css', __FILE__));
}

add_action( 'admin_enqueue_scripts', 'lbfa_enqueue_admin_scripts');
add_action('wp_enqueue_scripts', 'lbfa_enqueue_front_css');


class like_fa_btn_widget extends WP_Widget {

    // constructor
    function like_fa_btn_widget() {
        parent::WP_Widget(false, $name = __('Button with FontAwesome icons by LIKE.agency', 'lbfa') );
    }

    // widget form creation
    function form($instance) {

        $defaults = array(
            'lbfa_title' => '',
            'lbfa_icon' => 'fa-star',
            'lbfa_icon_color' => '#FFFFFF',
            'lbfa_icon_bgcolor' => '#000000',
            'lbfa_icon_position' => 'top',
            'lbfa_textarea' => '',
            'lbfa_url' => '#',
            'lbfa_button_text' => 'click'
        );

        $instance = wp_parse_args( (array) $instance, $defaults );
?>
<script>
    jQuery(document).ready(function($) {
        $('#widgets-right .lbfa_colorPicker').wpColorPicker();
    });

    jQuery(document).ajaxComplete(function(e, xhr, options) {
        if (xhr.responseText) {
            jQuery('#widgets-right .lbfa_colorPicker').wpColorPicker();
        }
    });
</script>

<div class="lbfa-options">
    <label for="<?php echo $this->get_field_id('lbfa_title'); ?>" class="lbfa-opt-heading"><?php _e('Widget title', 'lbfa'); ?>:</label>
    <input type="text" id="<?php echo $this->get_field_id('lbfa_title'); ?>" name="<?php echo $this->get_field_name('lbfa_title'); ?>" value="<?php echo esc_attr($instance['lbfa_title']); ?>">
    <label for="<?php echo $this->get_field_id('lbfa_title_heading');?>"  class="lbfa-opt-heading"><?php _e('Title heading tag', 'lbfa'); ?>: </label>
    <select id="<?php echo $this->get_field_id('lbfa_title_heading'); ?>" name="<?php echo $this->get_field_name('lbfa_title_heading'); ?>" >
        <option <?php selected( $instance['lbfa_title_heading'], 'h1'); ?> value="h1">h1</option> 
        <option <?php selected( $instance['lbfa_title_heading'], 'h2'); ?> value="h2">h2</option> 
        <option <?php selected( $instance['lbfa_title_heading'], 'h3'); ?> value="h3">h3</option> 
        <option <?php selected( $instance['lbfa_title_heading'], 'h4'); ?> value="h4">h4</option> 
        <option <?php selected( $instance['lbfa_title_heading'], 'h5'); ?> value="h5">h5</option> 
        <option <?php selected( $instance['lbfa_title_heading'], 'h6'); ?> value="h6">h6</option> 
    </select>
    <label for="<?php echo $this->get_field_id('lbfa_textarea'); ?>" class="lbfa-opt-heading"><?php _e('Content', 'lbfa'); ?>:</label>
    <textarea id="<?php echo $this->get_field_id('lbfa_textarea'); ?>" name="<?php echo $this->get_field_name('lbfa_textarea'); ?>" style="resize: none;"><?php echo  esc_attr( $instance['lbfa_textarea']); ?></textarea>	
    <label for="<?php echo $this->get_field_id('lbfa_icon'); ?>" class="lbfa-opt-heading"><?php _e('FontAwesome icon class', 'lbfa'); ?>:</label>
    <input type="text" id="<?php echo $this->get_field_id('lbfa_icon'); ?>" name="<?php echo $this->get_field_name('lbfa_icon'); ?>" value="<?php echo esc_attr($instance['lbfa_icon']); ?>"><br />
    <span class="lbfa-opt-info">&#40;<a href="http://fontawesome.io/cheatsheet/" target="_blank" rel="nofollow"><?php _e('FontAwesome Cheatsheet', 'lbfa')?></a>, <?php echo _e('use e.g. ', 'lbfa');?><b style="color:red;">fa-star</b>&#41;</span>
    <input type="checkbox" id="<?php echo $this->get_field_id('lbfa_icon_style'); ?>" name="<?php echo $this->get_field_name('lbfa_icon_style'); ?>" <?php echo $instance['lbfa_icon_style'] ? "checked" : ""; ?>><label for="<?php echo $this->get_field_id('lbfa_icon_style'); ?>"><?php _e('Icon in circle', 'lbfa'); ?>?</label>
    <div class="lbfa-pickers">
        <label for="<?php echo $this->get_field_id( 'lbfa_icon_color' ); ?>" class="lbfa-opt-heading"><?php _e( 'Icon color', 'lbfa' ); ?>:</label>
        <input class="lbfa_colorPicker" type="text" id="<?php echo $this->get_field_id( 'lbfa_icon_color' ); ?>" name="<?php echo $this->get_field_name( 'lbfa_icon_color' ); ?>" value="<?php echo esc_attr( $instance['lbfa_icon_color']);  ?>" />
        <label for="<?php echo $this->get_field_id( 'lbfa_icon_bgcolor' ); ?>" class="lbfa-opt-heading"><?php _e( 'Icon background color', 'lbfa' ); ?>:</label>
        <input class="lbfa_colorPicker" type="text" id="<?php echo $this->get_field_id( 'lbfa_icon_bgcolor' ); ?>" name="<?php echo $this->get_field_name( 'lbfa_icon_bgcolor' ); ?>" value="<?php echo esc_attr( $instance['lbfa_icon_bgcolor']);  ?>" />
    </div>
    <div class="lbfa-icon-styles">
        <label for="<?php echo $this->get_field_id('lbfa_icon_position')?>" class="lbfa-opt-heading"><?php _e('Icon position', 'lbfa')?>:</label>
        <input type="radio" name="<?php echo $this->get_field_name('lbfa_icon_position');?>" value="top" <?php checked('top', $instance['lbfa_icon_position']); ?> /><span><?php _e('Top', 'lbfa') ?></span><br />
        <input type="radio" name="<?php echo $this->get_field_name('lbfa_icon_position');?>" value="left" <?php checked('left', $instance['lbfa_icon_position']); ?> /><span><?php _e('Left', 'lbfa') ?></span><br />
        <input type="radio" name="<?php echo $this->get_field_name('lbfa_icon_position');?>" value="right" <?php checked('right', $instance['lbfa_icon_position']); ?> /><span><?php _e('Right', 'lbfa') ?></span><br />
    </div>
</div>
<div class="lbfa-options lbfa-divider">
    <input type="checkbox" class="lbfa-chbox-is-link" id="<?php echo $this->get_field_id('lbfa_is_link'); ?>" name="<?php echo $this->get_field_name('lbfa_is_link'); ?>" <?php echo $instance['lbfa_is_link'] ? "checked" : ""; ?>>
    <label for="<?php echo $this->get_field_id('lbfa_is_link'); ?>"><?php _e('Add link', 'lbfa'); ?>?</label>
    <div class="lbfa-is-link-opt">
        <div class="lbfa-url-opt">
            <label for="<?php echo $this->get_field_id('lbfa_url'); ?>" class="lbfa-opt-heading"><?php  _e('Link url', 'lbfa'); ?>: </label>
            <input type="text" id="<?php echo $this->get_field_id('lbfa_url'); ?>" name="<?php echo $this->get_field_name('lbfa_url'); ?>" value="<?php echo esc_attr($instance['lbfa_url']); ?>">
        </div>
        <input type="checkbox" class="chbox-tblank" id="<?php echo $this->get_field_id('lbfa_url_tblank'); ?>" name="<?php echo $this->get_field_name('lbfa_url_tblank'); ?>" <?php echo $instance['lbfa_url_tblank'] ? "checked" : ""; ?>>
        <label for="<?php echo $this->get_field_id('lbfa_url_tblank'); ?>"> <?php _e('Open link in new window', 'lbfa'); ?>?</label><br />
        <input type="checkbox" class="chbox-nfoll" id="<?php echo $this->get_field_id('lbfa_url_nfoll'); ?>" name="<?php echo $this->get_field_name('lbfa_url_nfoll'); ?>" <?php echo $instance['lbfa_url_nfoll'] ? "checked" : ""; ?>>
        <label for="<?php echo $this->get_field_id('lbfa_url_nfoll'); ?>"><?php _e('No follow', 'lbfa');?>?</label><br />
        <div>
            <input type="checkbox" class="lbfa-chbox-btn" id="<?php echo $this->get_field_id('lbfa_button'); ?>" name="<?php echo $this->get_field_name('lbfa_button'); ?>" <?php echo $instance['lbfa_button'] ? "checked" : ""; ?>>
            <label for="<?php echo $this->get_field_id('lbfa_button'); ?>"><?php _e('Link as button', 'lbfa'); ?>?</label>
            <div class="lbfa-inp-btn">
                <label for="<?php echo $this->get_field_id('lbfa_button_text'); ?>" class="lbfa-opt-heading"><?php _e('Button text', 'lbfa');?>:</label>
                <input type="text" id="<?php echo $this->get_field_id('lbfa_button_text'); ?>" name="<?php echo $this->get_field_name('lbfa_button_text'); ?>" value="<?php echo $instance['lbfa_button_text']; ?>">
                <div class="lbfa-btn-style">
                    <input type="checkbox" class="lbfa-chbox-btn-class" id="<?php echo $this->get_field_id('lbfa_custom'); ?>" name="<?php echo $this->get_field_name('lbfa_custom'); ?>" <?php echo $instance['lbfa_custom'] ? "checked" : ""; ?>>
                    <label for="<?php echo $this->get_field_id('lbfa_custom'); ?>"><?php _e('Custom class for button', 'lbfa'); ?>?</label>
                    <div class="lbfa-inp-btn-class">
                        <span class="lbfa-opt-info"><?php _e('Careful: the default button class provided by this widget will be overwritten.', 'lbfa');?></span>
                        <label for="<?php echo $this->get_field_id('lbfa_button_class'); ?>" class="lbfa-opt-heading"><?php _e('Custom class name for button', 'lbfa'); ?>:</label>
                        <input type="text" id="<?php echo $this->get_field_id('lbfa_button_class'); ?>" name="<?php echo $this->get_field_name('lbfa_button_class'); ?>" value="<?php echo $instance['lbfa_button_class'];?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    }
    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance = $new_instance;
        // Fields
        $instance['lbfa_title'] = strip_tags($new_instance['lbfa_title']);
        $instance['lbfa_title_heading'] = $new_instance['lbfa_title_heading'];
        $instance['lbfa_icon'] = strip_tags($new_instance['lbfa_icon']);
        $instance['lbfa_icon_style'] = $new_instance['lbfa_icon_style'];
        $instance['lbfa_icon_color'] = $new_instance['lbfa_icon_color'];
        $instance['lbfa_icon_bgcolor'] = $new_instance['lbfa_icon_bgcolor'];
        $instance['lbfa_icon_position'] = $new_instance['lbfa_icon_position'];
        $instance['lbfa_textarea'] = strip_tags($new_instance['lbfa_textarea']);
        $instance['lbfa_is_link'] = $new_instance['lbfa_is_link'];
        $instance['lbfa_url'] = strip_tags($new_instance['lbfa_url']);
        $instance['lbfa_url_tblank'] = $new_instance['lbfa_url_tblank'];
        $instance['lbfa_url_nfoll'] = $new_instance['lbfa_url_nfoll'];
        $instance['lbfa_button'] = $new_instance['lbfa_button'];
        $instance['lbfa_button_text'] = strip_tags($new_instance['lbfa_button_text']);
        $instance['lbfa_custom'] = $new_instance['lbfa_custom'];
        $instance['lbfa_button_class'] = strip_tags($new_instance['lbfa_button_class']);
        return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['lbfa_title']);
        $hTag = $instance['lbfa_title_heading']; 
        $icon = $instance['lbfa_icon'];
        $iconStyle = $instance['lbfa_icon_style'];
        $iconColor = $instance['lbfa_icon_color'];
        $iconBgColor = $instance['lbfa_icon_bgcolor'];
        $iconPosition = $instance['lbfa_icon_position'];
        $textarea = $instance['lbfa_textarea'];
        $is_link = $instance['lbfa_is_link'];
        $is_button = $instance['lbfa_button'];
        $is_custom = $instance['lbfa_custom'];

        if ($is_link) {
            $url = $instance['lbfa_url'];
            $tblank = $instance['lbfa_url_tblank'];
            $nfoll = $instance['lbfa_url_nfoll'];
            if ($tblank) {
                $blank = "target='_blank'";
            }
            if ($nfoll) {
                $nofollow = "rel='nofollow'";
            }
            $tagOpen = '<a href="'.$url.'" '.$blank.' '.$nofollow.'>';
            $tagClose = '</a>';
        } else {
            $tagOpen = "";
            $tagClose = "";
        }

        if ($hTag) {
            $heading = $hTag;
        } else {
            $heading = 'h2';
        }

        echo $before_widget;
        // Display the widget
        echo '<div class="widget-text lbfa-widget-box">';

        // Check if title is set
        if ( $title ) {
            $widgetTitle = '<'.$heading.' class="lbfa-heading">'.$title.'</'.$heading.'>';
        }

        if ( $icon ) {
            if ($iconStyle !="") {
                $circle = ' lbfa-wrapper-icon--circle';
            }
            $fa_icon = '<div class="lbfa-col-icon"><div class="lbfa-wrapper-icon'.$circle.'" style="background-color:'.$iconBgColor.';"><i class="fa '.$icon.'" style="color:'.$iconColor.';"></i></div></div>';
        }

        if ( $textarea ) {
            $textElement = '<div class="lbfa-col-text"><p class="lbfa-text">'.$textarea.'</p></div>';
            if ( $icon ) {
                $content = '<div class="lbfa-wrapper lbfa-wrapper--'.$iconPosition.'">'.$fa_icon.'</div>';
            } else {
                $content = '<p class="lbfa-text">'.$textarea.'</p>';
            }

        } else {
            $textElement = "";
            $content = '<div class="lbfa-wrapper">'.$fa_icon.'</div>';
        }

        if ($is_link) {
            if ($is_button) {
                if ($is_custom) {
                    $button_class = $instance['lbfa_button_class'];
                } else {
                    $button_class = "lbfa-button";
                }
                $button_text = $instance['lbfa_button_text'];
                $btnElement = '<div class="lbfa-wrapper lbfa-wrapper-button"><span class="'.$button_class.'">'.$button_text.'</span></div>';
            }
        }

        $content = '<div class="lbfa-wrapper lbfa-wrapper--'.$iconPosition.'">'.$fa_icon.$textElement.$btnElement.'</div>';

        echo $tagOpen.$widgetTitle.$content.$tagClose;

        echo '</div>';
        echo $after_widget;
    }
}

// register widget
add_action('widgets_init', create_function('', 'register_widget("like_fa_btn_widget");'));

add_action('plugins_loaded', 'lbfa_load_textdomain');
function lbfa_load_textdomain() {
    load_plugin_textdomain( 'lbfa', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

//add widget settings page with tabs
function lbfa_add_menu_item(){
    add_menu_page("Button with FontAwesome icons Settings", "Button with FontAwesome icons", "manage_options", "lbfa-settings", "lbfa_admin_settings_page", 'dashicons-edit', 99);
}
add_action('admin_menu', 'lbfa_add_menu_item');

//prepare actions to be hooked
function lbfa_admin_settings_page(){
    global $lbfa_active_tab;
    $lbfa_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'like-agency'; ?>
<h1><?php _e('Button with FontAwesome icons by LIKE.agency', 'lbfa');?></h1>
<h2 class="nav-tab-wrapper">
    <?php
    do_action( 'lbfa_settings_tab' );
    ?>
</h2>
<?php
    do_action( 'lbfa_settings_tab_content' );
}

//donate page functions
//--create donate tab
function lbfa_donate_tab(){
    global $lbfa_active_tab; ?>
<a class="nav-tab <?php echo $lbfa_active_tab == 'like-agency' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=lbfa-settings&tab=like-agency' ); ?>"><?php _e( 'LIKE.agency', 'lbfa' ); ?> </a>
<?php
}
//--render donate tab
function lbfa_render_donate_page() {
    global $lbfa_active_tab;
    if ( '' || 'like-agency' != $lbfa_active_tab )
        return;
?>
<h3><?php _e( 'Show us some love!', 'lbfa' ); ?></h3>
<?php lbfa_donate();
}
//--hooks for prepared actions
add_action( 'lbfa_settings_tab', 'lbfa_donate_tab', 1 );
add_action( 'lbfa_settings_tab_content', 'lbfa_render_donate_page' );


//advanced settings page function
//--create advanced settings tab
function lbfa_custom_css_tab(){
    global $lbfa_active_tab; ?>
<a class="nav-tab <?php echo $lbfa_active_tab == 'custom-css' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=lbfa-settings&tab=custom-css' ); ?>"><?php _e( 'Advanced widget settings', 'lbfa' ); ?> </a>
<?php }
//--render advanced settings tab
function lbfa_render_custom_css_page() {
    global $lbfa_active_tab;
    if ( 'custom-css' != $lbfa_active_tab )
        return;
?>
<h3><?php _e( 'Custom CSS', 'lbfa' ); ?></h3>
<?php lbfa_settings();
}
//--hooks for prepared actions
add_action('lbfa_settings_tab', 'lbfa_custom_css_tab');
add_action( 'lbfa_settings_tab_content', 'lbfa_render_custom_css_page' );

//tabs content functions
//--donate page content
function lbfa_donate(){ ?>
<div class="wrap lbfa-contribution">
<p><?php _e('This plugin is available <strong>free of charge</strong>. However, if you enjoyed it and would like to support us, you can use the PayPal donate link below to contribute. Thank you!.', 'lbfa');?></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHmAYJKoZIhvcNAQcEoIIHiTCCB4UCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYA7kwDZRaD/HoWN3Q5mX73ihgCaGEjpPUU2UqeSVMGymMTvZC/xHuTbtMJYF54BaTrRKy21ZQVSDXmHBmz9SXLphP6YjGceZsKteuTYnyC0hmHIJ0iv9iNDqaohculLW+LhWZGDHLTvx1wgFU7fa4mdrq6t00Jt+/5jvhPYNWAUIzELMAkGBSsOAwIaBQAwggEUBgkqhkiG9w0BBwEwFAYIKoZIhvcNAwcECOOCc/di+j4hgIHwdDLUbdZeYKgzApXvwdjuCaAbgeOP8yavMqYqoURXpeZl+/CmRgxlT/9UWJ9XRsA0D0e9NctSEcufvKCcwwMLYSlyOw58+rOwLoYOulDICIPqUrUzWmC2RC9PgtGnjyrdX1YmHVwNNGTP62eaDkLYtCbg49vmdZBXb5jOCkhX1xfqs0ZOL2qmeCrxXbeL7+tVnznu3xfDMTdt2NRpiJk700xRCBgDPiMNmAZ7dYuyP7ZG3DCsVUU1/H0X8yQi/V3W1Ef+kIhR32DrEV//2t5E76EhXWaCwJW+1ERuckFgm3PdRAkaOMrV7cd4WiLaR8ZRoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTcxMTA2MTA1OTUxWjAjBgkqhkiG9w0BCQQxFgQUzsEYrsUZN2TAaSADR4t92i1ldnYwDQYJKoZIhvcNAQEBBQAEgYAE0JwOyIZ45ONbVxkM0K/pOfbi7aYZf2A2ik+2FF1N7zAKcnBo3fffWlKdlNXsaoL+jA16Mcmf3Bgvr3MydZ0YImnVuYbtQgF1j0L4bezcCT5CJCZfVU/NoaZiBvgxSKuW3JzcCtjbIPllLpJyU4lnXLgxBhuVOXp67Bo29hgWuw==-----END PKCS7-----
                                                 ">
    <input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<?php }

//--advanced settings page content
function lbfa_settings() { ?>
<div class="wrap">
    <p><?php _e('If you want to add custom style for widget elements, you can overwrite default styles by using following widget classes in your custom stylesheet. Just copy, paste, edit and save them in the <strong>Custom CSS Stylesheet</strong> section.','lbfa');?></p>
    <div class="lbfa-opt-css">
        <div class="lbfa-opt-examples">
            <h2><?php _e('Class names used for widget elements', 'lbfa');?></h2>
            <ul>
                <li><strong><?php _e('Widget title style', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-heading</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-heading { 
                    <pre>margin-bottom: 20px;</pre>
                    <pre>text-align: center;</pre> }
                </span>
                <li><strong><?php _e('Text content style', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-text</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-text { 
                    <pre>margin-bottom: 20px;</pre> }
                </span>
                <li><strong><?php _e('Text content style for left aligned icon', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-wrapper--left .lbfa-text</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-wrapper--left .lbfa-text { 
                    <pre>text-align: left;</pre>
                    }
                </span>
                <li><strong><?php _e('Text content style for right aligned icon', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-wrapper--right .lbfa-text</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-wrapper--right .lbfa-text { 
                    <pre>text-align: left;</pre>
                    }
                </span>
                <li><strong><?php _e('Text content style for top aligned icon', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-wrapper--top .lbfa-text</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-wrapper--top .lbfa-text { 
                    <pre>text-align: center;</pre>
                    <pre>max-width: 450px;</pre>
                    <pre>margin-left: auto;</pre>
                    <pre>margin-right: auto;</pre>
                    }
                </span>
                <li><strong><?php _e('FontAwesome icon style', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-wrapper-icon .fa </strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-wrapper-icon .fa { 
                    <pre>position: absolute;</pre> 
                    <pre>top: 50%;</pre> 
                    <pre>left: 50%;</pre> 
                    <pre>transform: translateX(-50%) translateY(-50%);</pre> 
                    <pre>font-size: 46px;</pre> 
                    }
                </span>
                <li><strong><?php _e('FontAwesome icon wrapper style', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-wrapper-icon</strong> selector, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-wrapper-icon { 
                    <pre>width: 100px;</pre>
                    <pre>height: 100px;</pre>
                    <pre>border: none;</pre>
                    <pre>position: relative;</pre>
                    <pre>border-radius: 3px;</pre>
                    <pre>margin: 0 auto;</pre>
                    }
                </span>
                <li><strong><?php _e('Button style', 'lbfa');?>:</strong><br /><?php _e('use <strong>.lbfa-button</strong> selector, and <strong>.lbfa-button:hover </strong> selector for hover effect, e.g.', 'lbfa');?></li>
                <span class="lbfa-opt-desc">
                    .lbfa-button { 
                    <pre>display: inline-block;</pre>
                    <pre>padding: 10px 30px;</pre>
                    <pre>border: 1px solid #444;</pre>
                    <pre>border-radius: 3px;</pre>
                    <pre>color: #444;</pre>
                    <pre>text-align: center;</pre>
                    <pre>text-decoration: none;</pre>
                    <pre>text-transform: uppercase;</pre>
                    <pre>font-size: .75rem;</pre>
                    <pre>font-weight: 600;</pre>
                    <pre>letter-spacing: 2px;</pre>
                    <pre>line-height: 1;</pre>
                    }<br />
                    .lbfa-button:hover { 
                    <pre>background-color: #F2F2F2;</pre> 
                    }
                </span>
            </ul>
        </div>
        <div class="lbfa-css-form">
            <form action="options.php" method="post">
                <?php
                          settings_fields("section");
                          do_settings_sections("lbfa-options");      
                          submit_button(); 
                ?>  
            </form>
        </div>
    </div>
</div>
<?php }

//--advanced settings page form handling
function lbfa_display_css_element(){?>
<textarea name="lbfa_custom_css" id="lbfa_custom_css" value="<?php echo get_option('lbfa_custom_css'); ?>"><?php echo get_option('lbfa_custom_css');?></textarea>
<?php }

function lbfa_display_fields(){
    add_settings_section('section', __('Custom CSS stylesheet', 'lbfa'), null, 'lbfa-options');
    add_settings_field('lbfa_custom_css', __('Paste your custom styles here using provided selectors:', 'lbfa'), 'lbfa_display_css_element', 'lbfa-options', 'section');
    register_setting('section', 'lbfa_custom_css');
}
add_action('admin_init', 'lbfa_display_fields');

//-- add inline custom stylesheet 
function lbfa_upload_custom_styles() {
    wp_enqueue_style('lbfa-custom-style', plugins_url('/inc/lbfa_custom_stylesheet.css', __FILE__));
    $custom_css = get_option('lbfa_custom_css');
    wp_add_inline_style( 'lbfa-custom-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'lbfa_upload_custom_styles' );