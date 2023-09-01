<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

/**
 * Helper shortcode for retrieving TEC Event data outside the TEC loop.
 *
 * @param      array  $atts {
 *   @type  integer  $event_id The event ID.
 *   @type  string   $type 		 The type of data to retrieve
 * }
 *
 * @return     string  The event data returned in HTML.
 */
function sfg_get_tribe_event_data( $atts ){
	$args = shortcode_atts( [
		'event_id' 	=> null,
		'type'			=> 'details',
	], $atts );
	$html = '';

	$event_id = $args['event_id'];
	if( ! $event_id ){
		$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$event_id = url_to_postid( $actual_link );
	}

	if( ! tribe_is_event( $event_id ) )
		return;

	switch ( $args['type'] ) {
		case 'featured_image':
			$featured_image = tribe_event_featured_image( $event_id, 'full', false, false );
			if( empty( $featured_image ) )
				return '<style>#hero-featured-image{display: none;}</style>';
			$html = '<img src="' . $featured_image . '" alt="' . esc_attr( get_the_title( $event_id ) ) . '" />';
			break;

		case 'post_content':
			$html = get_the_content( null, false, $event_id );
			break;

		case 'form':
			$disable_registration_form = get_field( 'disable_registration_form', $event_id );
			if( $disable_registration_form )
				return '';

		  $event = tribe_get_event( $event_id );
		  $dates = $event->dates;
		  $start_date = ( ! is_null( $dates ) )? $dates->start->format('U') : strtotime( '+3 weeks' ); // Added default start_date to prevent fatal error while in Elementor editor
		  $current_date = current_time( 'timestamp' );
		  $event_details = tribe_events_event_schedule_details( $event_id );
		  $event_details = preg_replace( '/\<div class="recurringinfo"\>.*<\/div>/', '', $event_details );
		  $event_details = strip_tags( $event_details );
	    if( $start_date < $current_date ){
		    $html = '<p>Registration: This event has already past. Please see our <a href="' . $args['events_page'] . '">events page</a> for upcoming webinars.</p>';
		  } else {
		  	$html = gravity_form( 1, false, false, false, [ 'datetime' => $event_details ], true, 99, false );
		  }
			break;

		case 'title':
			$html = '<h1>' . get_the_title( $event_id ) . '</h1><style>#compare-your-options{display: none;}</style>';
			break;

		default:
			$html = tribe_events_event_schedule_details( $event_id, '<div class="event-details">', '</div>' );
			break;
	}
	return $html;
}
add_shortcode( 'sfg_tribe_event_data', 'sfg_get_tribe_event_data' );