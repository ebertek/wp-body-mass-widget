<?php
/*
Plugin Name: Body Mass Widget
Plugin URI: https://biombodigital.com
Description: Adds a widget that shows a BMI calculater. You can change all the labels and it is totally free.
Author: Michelle Salabert
Version: 1.0
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
*/

add_action( 'widgets_init', 'bodycalculater_register_widgets' );

function bodycalculater_register_widgets() {
	register_widget( 'BodyMass' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'bodycalculater_register_plugin_styles' );

//Register style sheet.
function bodycalculater_register_plugin_styles() {
	wp_register_style( 'body-mass-widget', plugins_url( '/body-mass-widget/css/body-mass-widget.css' ) );
	wp_enqueue_style( 'body-mass-widget' );
}

// Body Mass Widget Class.
class BodyMass extends WP_Widget {

	function BodyMass() {
		$widget_ops = array('description' => 'A bmi calculater widget by michellesalabert.com');
		$title ='Body Mass Calculater';
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
		$underweight = $instance ['underweight'];
		$normal = $instance ['normal'];
		$overweight = $instance ['overweight'];
		$obese = $instance ['obese'];
		$link = $instance['link'];
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

			<input id="submit" onclick="bodymass_calculate()" type="button" value="<?php echo $calculate; ?>" />

			<input id="reset" onclick="resetform()" type="button" value="<?php echo $reset; ?>" />

			<div id="bm_result"></div>

			<?php if($link == 'on') { ?>

					<a class="poweredby" href="http://michellesalabert.com" target="_blank">Powered by Michelle Salabert</a>
			<?php } ?>

		</div>

		<!--BMI Calculate Javascript code-->
		<script type="text/javascript">

		function bodymass_calculate() {

		var weight = document.getElementById('weight').value;
		var height = document.getElementById('height').value/100;
		var bm = weight/(height*height);
		var msg = '';

		if(bm < 18.5) {
			 msg = '<?php echo $underweight; ?>'; }
		else if(bm > 18.5 && bm < 25) {
			 msg = '<?php echo $normal; ?>'; }
		else if(bm > 25 && bm < 30) {
			 msg = '<?php echo $overweight; ?>'; }
		else if(bm > 30) {
			 msg = '<?php echo $obese; ?>'; }

		if(weight > 0 && height > 0 && weight != null && height != null) {
		bm_result.innerHTML = '<p><?php echo $yourbm; ?><strong>' + bm.toFixed(2) + '</strong></p><p><strong>' + msg + '</strong></p>'; } else {
		alert('<?php echo $error; ?>');
	}
}
		<!--Reset button Javacript code-->
		function resetform() {

		document.getElementById('weight').value='';
		document.getElementById('height').value='';
		bm_result.innerHTML = '<p>';
}

	</script>

		<?php echo $after_widget;
}

//Update the options
function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['border'] = $new_instance['border'];
		$instance['calculate'] = $new_instance['calculate'];
		$instance['yourbm'] = $new_instance['yourbm'];
		$instance ['underweight'] = $new_instance ['underweight'];
		$instance ['normal'] = $new_instance ['normal'];
		$instance ['overweight'] = $new_instance ['overweight'];
		$instance ['obese'] = $new_instance ['obese'];
		$instance ['height'] = $new_instance ['height'];
		$instance ['weight'] = $new_instance ['weight'];
		$instance['error'] = $new_instance['error'];
		$instance['link'] = $new_instance['link'];
$instance['reset'] = $new_instance['reset'];

		return $instance;
	}

//The widget configuration form back end.
	function form( $instance ) {

		$defaults = array( 'title' => 'Body Mass Calculater',
							 'calculate' => 'Calculate',
							 'reset' => 'Reset',
							 'yourbm' => 'Your BMI is = ',
							 'underweight' => 'Underweight',
							 'normal' => 'Normal',
							 'overweight' => 'Overweight',
							 'obese' => 'Obese',
							 'weight' => 'Weight',
							 'height' => 'Height',
							 'error' => 'Please enter valid information!',
							 'border' => '#c5c8c4',
							 'link' => '' );
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
			<label for="<?php echo $this->get_field_id( 'underweight' ); ?>">Underweight</label><br/>
			<input id="<?php echo $this->get_field_id('underweight' ); ?>" name="<?php echo $this->get_field_name('underweight'); ?>" value="<?php echo $instance['underweight']; ?>" class="widefat" type="text" />
			</p>

		 <p>
			<label for="<?php echo $this->get_field_id( 'normal' ); ?>">Normal</label><br/>
			<input id="<?php echo $this->get_field_id('normal' ); ?>" name="<?php echo $this->get_field_name('normal'); ?>" value="<?php echo $instance['normal']; ?>" class="widefat" type="text" />
			</p>

			<p>
			<label for="<?php echo $this->get_field_id( 'overweight' ); ?>">Overweight</label><br/>
			<input id="<?php echo $this->get_field_id('overweight' ); ?>" name="<?php echo $this->get_field_name('overweight'); ?>" value="<?php echo $instance['overweight']; ?>" class="widefat" type="text" />
			</p>

			<p>
			<label for="<?php echo $this->get_field_id( 'obese' ); ?>">Obese</label><br/>
			<input id="<?php echo $this->get_field_id('obese' ); ?>" name="<?php echo $this->get_field_name('obese'); ?>" value="<?php echo $instance['obese']; ?>" class="widefat" type="text" />
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

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>">Support me! Link to michellesalabert.com <input id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" class="checkbox" type="checkbox" <?php checked($instance['link'], 'on'); ?> /></label>
		</p> 
<?php }
}?>