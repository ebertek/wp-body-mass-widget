<?php
/*
Plugin Name:       WP Body Mass Widget
Plugin URI:        https://github.com/ebertek/wp-body-mass-widget
GitHub Plugin URI: ebertek/wp-body-mass-widget
GitHub Branch:     master
Description:       Adds a widget that shows a BMI calculator.
Version:           1.4
Author:            ebertek
Author URI:        https://ebertek.com
Text Domain:       wp-body-mass-widget
Domain Path:       /languages

  Original work Copyright 2014 Michelle Salabert (michelle@biombodigital.com)
  Modified work Copyright 2016 David Ebert (ebertek@gmail.com)
  
  This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

  // Register style sheet.
  add_action('wp_enqueue_scripts', 'bodycalculator_register_plugin_styles');
  function bodycalculator_register_plugin_styles() {
    wp_register_style('wp-body-mass-widget', plugins_url('/wp-body-mass-widget/css/wp-body-mass-widget.css'));
    wp_enqueue_style('wp-body-mass-widget');
  }

  // i18n
  add_action('plugins_loaded', 'bodycalculator_load_textdomain');
  function bodycalculator_load_textdomain() {
    $domain = 'wp-body-mass-widget';
    $locale = apply_filters('plugin_locale', get_locale(), $domain);
    load_textdomain($domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo');
    load_plugin_textdomain($domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages');
  }

  // Body Mass Widget Class.
  class BodyMass extends WP_Widget {

    function __construct() {
      $title = 'Body Mass Calculator';
      $widget_ops = array('description' => 'A BMI calculator widget');
      parent::__construct(false, $title, $widget_ops);
    }

    // Declare all labels that appear on widget admin
    function widget($args, $instance) {
      extract($args);

      echo $before_widget;
      if (!empty($instance['title'])) {
        $title = apply_filters('widget_title', $instance['title'], $instance);
        echo $before_title . $title . $after_title;
      }
      ?>

      <!-- WP BMI Calculator -->
      <div id="calculate_bodymass">
        <?php
          if (!empty($instance['ad'])) {
            echo do_shortcode($instance['ad']);
          }
        ?>
        <table>
          <tr><td><label for="weight"><?php _e('Weight', 'wp-body-mass-widget'); ?>:</label></td><td><input type="text" name="weight" id="weight" /><span> kg</span></td></tr>
          <tr><td><label for="height"><?php _e('Height', 'wp-body-mass-widget'); ?>:</label></td><td><input type="text" name="height" id="height" /><span> cm</span></td></tr>
        </table>
        <div id="wpbmbtn">
          <input id="wpbmsubmit" onclick="bodymass_calculate()" type="button" value="<?php _e('Calculate', 'wp-body-mass-widget'); ?>" />
          <input id="wpbmreset" onclick="bodymass_resetform()" type="button" value="<?php _e('Reset', 'wp-body-mass-widget'); ?>" />
        </div>
        <div id="bm_result"></div>
      </div>

      <script type="text/javascript">
        var bm_result = document.getElementById('bm_result');
        function bodymass_calculate() {
          var weight = document.getElementById('weight').value;
          var height = document.getElementById('height').value/100;
          var bm     = weight / (height * height);
          var msg    = '';
          if(bm < 15) {
            msg = '<?php _e('Very severely underweight', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 15 && bm < 16) {
            msg = '<?php _e('Severely underweight', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 16 && bm < 18.5) {
            msg = '<?php  _e('Underweight', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 18.5 && bm < 25) {
            msg = '<?php _e('Normal (healthy weight)', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 25 && bm < 30) {
            msg = '<?php _e('Overweight', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 30 && bm < 35) {
            msg = '<?php _e('Obese Class I (Moderately obese)', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 35 && bm < 40) {
            msg = '<?php _e('Obese Class II (Severely obese)', 'wp-body-mass-widget'); ?>';
          } else if (bm >= 40) {
            msg = '<?php _e('Obese Class III (Very severely obese)', 'wp-body-mass-widget'); ?>';
          }
          if (weight > 0 && height > 0 && weight !== null && height !== null && weight < 635 && height < 272) {
            bm_result.innerHTML = '<p><?php _e('Your BMI:', 'wp-body-mass-widget'); ?> <strong>' + bm.toFixed(2) + '</strong></p><p><strong>' + msg + '</strong></p>';
          } else {
            bm_result.innerHTML = '<p><?php _e('Please enter valid information!', 'wp-body-mass-widget'); ?></p>';
          }
        }

        function bodymass_resetform() {
          document.getElementById('weight').value='';
          document.getElementById('height').value='';
          bm_result.innerHTML = '<p>';
        }
      </script>
      <!-- WP BMI Calculator -->

      <?php echo $after_widget;
    }  // function widget

    // Update the options
    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['ad']    = $new_instance['ad'];
      return $instance;
    }

    //The widget configuration form back end.
    function form($instance) {

      $defaults = array(
        'title' => 'Body Mass Calculator',
      );
      $instance = wp_parse_args((array) $instance, $defaults); ?>

      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-body-mass-widget'); ?><br/>
          <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class=" " type="text" /></label>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('ad'); ?>"><?php _e('Code:', 'wp-body-mass-widget'); ?><br/>
          <textarea id="<?php echo $this->get_field_id('ad'); ?>" name="<?php echo $this->get_field_name('ad'); ?>" class="widefat"><?php if (!empty($instance['ad'])) echo $instance['ad']; ?></textarea></label>
      </p>

    <?php
    }  // function form
  }    // class BodyMass
  function widget_textdomain() {
    $domain = $this->plugin_slug;
    $locale = apply_filters('plugin_locale', get_locale(), $domain);
    load_textdomain($domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo');
    load_plugin_textdomain($domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
  }
  add_action('widgets_init', create_function('', 'return register_widget("BodyMass");'));
?>
