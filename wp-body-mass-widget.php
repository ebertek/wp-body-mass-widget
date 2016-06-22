<?php
/*
Plugin Name: Body Mass Widget
Plugin URI: https://biombodigital.com
Description: Adds a widget that shows a BMI calculator. You can change all the labels and it is totally free.
Author: Salabert + Ebert
Version: 1.1
Author URI: http://biombodigital.com

  Copyright 2014 Michelle Salabert (michelle@biombodigital.com)
  
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

  Modified 2016 @ebertek

*/

add_action( 'widgets_init', 'bodycalculater_register_widgets' );

function bodycalculater_register_widgets() {
  register_widget( 'BodyMass' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'bodycalculater_register_plugin_styles' );

//Register style sheet.
function bodycalculater_register_plugin_styles() {
  wp_register_style( 'wp-body-mass-widget', plugins_url( '/wp-body-mass-widget/css/wp-body-mass-widget.css' ) );
  wp_enqueue_style( 'wp-body-mass-widget' );
}

// Body Mass Widget Class.
class BodyMass extends WP_Widget {

  function BodyMass() {
    $widget_ops = array('description' => 'A BMI calculator widget by michellesalabert.com');
    $title ='Body Mass Calculator';
    parent::__construct( false, $title, $widget_ops );
  }

// Declare all labels that appear on widget admin
function widget( $args, $instance ) {
    extract( $args );

    $title = apply_filters('widget_title', $instance['title'] );
    $border = $instance['border'];
    $calculate = $instance['calculate'];
    $yourbm = $instance['yourbm'];
    $error = $instance['error'];
    $weight = $instance ['weight'];
    $height = $instance ['height'];
    $bmikat1 = $instance ['bmikat1'];
    $bmikat2 = $instance ['bmikat2'];
    $bmikat3 = $instance ['bmikat3'];
    $bmikat4 = $instance ['bmikat4'];
    $bmikat5 = $instance ['bmikat5'];
    $bmikat6 = $instance ['bmikat6'];
    $bmikat7 = $instance ['bmikat7'];
    $bmikat8 = $instance ['bmikat8'];
    $reset = $instance['reset'];

    echo $before_widget; ?>

    <style type="text/css">

      #calculate_bodymass {
        border: 1px solid <?php echo $border; ?>;
      }

    </style>

     <!--Calculate table -->
     <div id="calculate_bodymass">

    <h2><?php echo $title; ?></h2>

      <table>

      <tr><td><label for="weight"><?php echo $weight; ?>:</label></td><td><input type="text" name="weight" id="weight" /><span>kg</span></td></tr>
      <tr><td><label for="height"><?php echo $height; ?>:</label></td><td><input type="text" name="height" id="height" /><span>cm</span></td></tr>

      </table>
<div style="margin: 0 auto; width: 200px;">
      <input id="submit" onclick="bodymass_calculate()" type="button" value="<?php echo $calculate; ?>" />

      <input id="reset" onclick="resetform()" type="button" value="<?php echo $reset; ?>" />
</div>
      <div id="bm_result"></div>

    </div>

    <!--BMI Calculate JavaScript code-->
    <script type="text/javascript">

    function bodymass_calculate() {

    var weight = document.getElementById('weight').value;
    var height = document.getElementById('height').value/100;
    var bm = weight/(height*height);
    var msg = '';

if(bm < 15) {
  msg = '<?php echo $bmikat1; ?>';
} else if(bm >= 15 && bm < 16) {
  msg = '<?php echo $bmikat2; ?>';
} else if(bm >= 16 && bm < 18.5) {
  msg = '<?php echo $bmikat3; ?>';
} else if(bm >= 18.5 && bm < 25) {
  msg = '<?php echo $bmikat4; ?>';
} else if(bm >= 25 && bm < 30) {
  msg = '<?php echo $bmikat5; ?>';
} else if(bm >= 30 && bm < 35) {
  msg = '<?php echo $bmikat6; ?>';
} else if(bm >= 35 && bm < 40) {
  msg = '<?php echo $bmikat7; ?>';
} else if(bm >= 40) {
  msg = '<?php echo $bmikat8; ?>';
}
    if(weight > 0 && height > 0 && weight != null && height != null && weight < 635 && height < 272) {
    bm_result.innerHTML = '<p><?php echo $yourbm; ?> <strong>' + bm.toFixed(2) + '</strong></p><p><strong>' + msg + '</strong></p>'; } else {
    bm_result.innerHTML = '<p><?php echo $error; ?></p>';
  }
}
    <!--Reset button JavaScript code-->
    function resetform() {

    document.getElementById('weight').value='';
    document.getElementById('height').value='';
    bm_result.innerHTML = '<p>';
}

  </script>

    <?php echo $after_widget;
}

// Update the options
function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['title']     = strip_tags( $new_instance['title'] );
    $instance['border']    = $new_instance['border'];
    $instance['calculate'] = $new_instance['calculate'];
    $instance['yourbm']    = $new_instance['yourbm'];
    $instance['bmikat1']   = $new_instance['bmikat1'];
    $instance['bmikat2']   = $new_instance['bmikat2'];
    $instance['bmikat3']   = $new_instance['bmikat3'];
    $instance['bmikat4']   = $new_instance['bmikat4'];
    $instance['bmikat5']   = $new_instance['bmikat5'];
    $instance['bmikat6']   = $new_instance['bmikat6'];
    $instance['bmikat7']   = $new_instance['bmikat7'];
    $instance['bmikat8']   = $new_instance['bmikat8'];
    $instance['height']    = $new_instance['height'];
    $instance['weight']    = $new_instance['weight'];
    $instance['error']     = $new_instance['error'];
    $instance['reset']     = $new_instance['reset'];

    return $instance;
  }

//The widget configuration form back end.
  function form( $instance ) {

    $defaults = array(
               'title'     => 'Body Mass Calculater',
               'calculate' => 'Calculate',
               'reset'     => 'Reset',
               'yourbm'    => 'Your BMI is = ',
               'bmikat1'   => 'Very severely underweight',
               'bmikat2'   => 'Severely underweight',
               'bmikat3'   => 'Underweight',
               'bmikat4'   => 'Normal (healthy weight)',
               'bmikat5'   => 'Overweight',
               'bmikat6'   => 'Obese Class I (Moderately obese)',
               'bmikat7'   => 'Obese Class II (Severely obese)',
               'bmikat8'   => 'Obese Class III (Very severely obese)',
               'weight'    => 'Weight',
               'height'    => 'Height',
               'error'     => 'Please enter valid information!',
               'border'    => '#c5c8c4'
    );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title<br/>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class=" " type="text" /></label>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'border' ); ?>">Border color</label><br/>
      <input id="<?php echo $this->get_field_id( 'border' ); ?>" name="<?php echo $this->get_field_name( 'border' ); ?>" value="<?php echo $instance['border']; ?>" class="widefat" type="text" />
    </p>

      <p>
      <label for="<?php echo $this->get_field_id( 'yourbm' ); ?>">Your BMI</label><br/>
      <input id="<?php echo $this->get_field_id('yourbm' ); ?>" name="<?php echo $this->get_field_name('yourbm'); ?>" value="<?php echo $instance['yourbm']; ?>" class="widefat" type="text" />
      </p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat1' ); ?>">Very severely underweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat1' ); ?>" name="<?php echo $this->get_field_name('bmikat1'); ?>" value="<?php echo $instance['bmikat1']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat2' ); ?>">Severely underweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat2' ); ?>" name="<?php echo $this->get_field_name('bmikat2'); ?>" value="<?php echo $instance['bmikat2']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat3' ); ?>">Underweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat3' ); ?>" name="<?php echo $this->get_field_name('bmikat3'); ?>" value="<?php echo $instance['bmikat3']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat4' ); ?>">Normal</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat4' ); ?>" name="<?php echo $this->get_field_name('bmikat4'); ?>" value="<?php echo $instance['bmikat4']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat5' ); ?>">Overweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat5' ); ?>" name="<?php echo $this->get_field_name('bmikat5'); ?>" value="<?php echo $instance['bmikat5']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat6' ); ?>">Moderately overweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat6' ); ?>" name="<?php echo $this->get_field_name('bmikat6'); ?>" value="<?php echo $instance['bmikat6']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat7' ); ?>">Severely overweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat7' ); ?>" name="<?php echo $this->get_field_name('bmikat7'); ?>" value="<?php echo $instance['bmikat7']; ?>" class="widefat" type="text" />
</p>
<p>
  <label for="<?php echo $this->get_field_id( 'bmikat8' ); ?>">Very severely overweight</label><br/>
  <input id="<?php echo $this->get_field_id('bmikat8' ); ?>" name="<?php echo $this->get_field_name('bmikat8'); ?>" value="<?php echo $instance['bmikat8']; ?>" class="widefat" type="text" />
</p>

    <p>
      <label for="<?php echo $this->get_field_id( 'weight' ); ?>">Weight</label><br/>
      <input id="<?php echo $this->get_field_id('weight' ); ?>" name="<?php echo $this->get_field_name('weight'); ?>" value="<?php echo $instance['weight']; ?>" class="widefat" type="text" />
      </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'height' ); ?>">Height</label><br/>
      <input id="<?php echo $this->get_field_id('height' ); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" class="widefat" type="text" />
      </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'error' ); ?>">Text: Error!</label><br/>
      <input id="<?php echo $this->get_field_id('error' ); ?>" name="<?php echo $this->get_field_name('error'); ?>" value="<?php echo $instance['error']; ?>" class="widefat" type="text" />
      </p>

     <p>
      <label for="<?php echo $this->get_field_id( 'calculate' ); ?>">Calculate your body mass<br/>
      <input id="<?php echo $this->get_field_id( 'calculate' ); ?>" name="<?php echo $this->get_field_name( 'calculate' ); ?>" value="<?php echo $instance['calculate']; ?>" class="widefat" type="text" /></label>
    </p>

<p>
      <label for="<?php echo $this->get_field_id( 'reset' ); ?>">Reset<br/>
      <input id="<?php echo $this->get_field_id( 'reset' ); ?>" name="<?php echo $this->get_field_name( 'reset' ); ?>" value="<?php echo $instance['reset']; ?>" class="widefat" type="text" /></label>
    </p>

<?php }
}?>
