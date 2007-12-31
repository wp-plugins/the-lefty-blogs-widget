<?php
/*
Plugin Name: Lefty Blogs Widget
Description: Adds a sidebar widget that let's you use Lefty Blogs rolls in your sidebar.  Eliminates the problem of the Widgets security feature preventing arbitrary JavaScript code.
Author: Thomas J. Belknap
Version: 0.1
Author URI: http://holisticnetworking.net
*/

function widget_leftyblogs_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our Lefty Blog roll.
	function widget_leftyblogs($args) {
		
		extract($args);

		// Each widget can store its own options. We keep strings here.
		$options = get_option('widget_leftyblogs');
		$state = $options['state'];
		$tz = $options['tz'];
		$number = $options['number'];

		// These lines generate our output. Widgets can be very complex
		// but as you can see here, they can also be very, very simple.
		echo $before_widget;
		$url_parts = $_SERVER['SERVER_NAME'];
		$output = '<script type="text/javascript" language="javascript" src="http://www.leftyblogs.com/cgi-bin/blogwire.cgi?feed='.$state.'&site='.$url_parts.'&tz='.$tz.'&n='.$number.'"></script>';
		echo $output;
		echo $after_widget;
	}

	// This is the function that outputs the form to let the users edit
	// the widget's title. It's an optional feature that users cry for.
	function widget_leftyblogs_control() {


		// Get our options and see if we're handling a form submission.
		$options = get_option('widget_leftyblogs');
		if ( !is_array($options) )
			$options = array('state'=>'newyork', 'tz'=>'est', 'number'=>'15');
		if ( $_POST['leftyblogs-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['state'] = strip_tags(stripslashes($_POST['leftyblogs-state']));
			$options['tz'] = strip_tags(stripslashes($_POST['leftyblogs-tz']));
			$options['number'] = strip_tags(stripslashes($_POST['leftyblogs-number']));
			update_option('widget_leftyblogs', $options);
		}

		// Be sure you format your options to be valid HTML attributes.
		$state = htmlspecialchars($options['state'], ENT_QUOTES);
		$tz = htmlspecialchars($options['tz'], ENT_QUOTES);
		$number = htmlspecialchars($options['number'], ENT_QUOTES);
		
		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="leftyblogs-state">' . __('State (no abbreviations, please!):', 'widgets') . ' <input style="width: 200px;" id="leftyblogs-state" name="leftyblogs-state" type="text" value="'.$state.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="leftyblogs-tz">' . __('Time Zone (est, etc.):', 'widgets') . ' <input style="width: 200px;" id="leftyblogs-tz" name="leftyblogs-tz" type="text" value="'.$tz.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="leftyblogs-number">' . __('Number of posts:', 'widgets') . ' <input style="width: 200px;" id="leftyblogs-number" name="leftyblogs-number" type="text" value="'.$number.'" /></label></p>';
		echo '<input type="hidden" id="leftyblogs-submit" name="leftyblogs-submit" value="1" />';
	}
	
	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Lefty Blogs', 'widgets'), 'widget_leftyblogs');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Lefty Blogs', 'widgets'), 'widget_leftyblogs_control', 300, 200);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_leftyblogs_init');

?>