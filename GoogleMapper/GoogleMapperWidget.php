<?php

// creates the widget. You'll probably want to make a smallish map if you plan on putting it into your sidebar.
// by Adam Brown -> http://adambrown.info/

function widget_googlemapper_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_googlemapper($args) {
		
		extract($args);

		$options = get_option('widget_googlemapper');
		$title = $options['title'];
		$which_map = '[gmap map:' . $options['which_map'] . ']';

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;

		echo gmapper_add_map ($which_map,true); // the 'true' keeps the function from printing a title.

		echo $after_widget;
	}

	function widget_googlemapper_control() {

		$options = get_option('widget_googlemapper');
		if ( !is_array($options) )	// default options
			$options = array('title'=>'', 'which_map'=>1);
		if ( $_POST['googlemapper-submit'] ) {	// updated options

			$options['title'] = strip_tags(stripslashes($_POST['googlemapper-title']));
			$options['which_map'] = strip_tags(stripslashes($_POST['googlemapper-which_map']));
			if ( !is_numeric($options['which_map']) )
				$options['which_map'] = 1;
			update_option('widget_googlemapper', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$which_map = htmlspecialchars($options['which_map'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="googlemapper-title">' . __('Title:') . ' <input style="width: 200px;" id="googlemapper-title" name="googlemapper-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="googlemapper-which_map">Map number: <input style="width: 200px;" id="googlemapper-which_map" name="googlemapper-which_map" type="text" value="'.$which_map.'" /></label></p>';
		echo '<input type="hidden" id="googlemapper-submit" name="googlemapper-submit" value="1" />';
		echo '<p><small><strong>Tip:</strong> Make your map narrow (probably less than 200 pixels wide, depending on your theme), and turn off zoom and directional controls.</small></p>';
	}
	
	register_sidebar_widget(array('Google Mapper', 'widgets'), 'widget_googlemapper');

	register_widget_control(array('Google Mapper', 'widgets'), 'widget_googlemapper_control', 300, 200);
}

add_action('widgets_init', 'widget_googlemapper_init');

?>