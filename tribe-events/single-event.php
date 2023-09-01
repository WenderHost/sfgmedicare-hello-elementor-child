<?php
/**
 * Single Event Template
 * Override of The Event Calendar's single-event.php.
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = Tribe__Events__Main::postIdHelper( get_the_ID() );

/**
 * Allows filtering of the event ID.
 *
 * @since 6.0.1
 *
 * @param int $event_id
 */
$event_id = apply_filters( 'tec_events_single_event_id', $event_id );

/**
 * Allows filtering of the single event template title classes.
 *
 * @since 5.8.0
 *
 * @param array  $title_classes List of classes to create the class string from.
 * @param string $event_id The ID of the displayed event.
 */
$title_classes = apply_filters( 'tribe_events_single_event_title_classes', [ 'tribe-events-single-event-title' ], $event_id );
$title_classes = implode( ' ', tribe_get_classes( $title_classes ) );

/**
 * Allows filtering of the single event template title before HTML.
 *
 * @since 5.8.0
 *
 * @param string $before HTML string to display before the title text.
 * @param string $event_id The ID of the displayed event.
 */
$before = apply_filters( 'tribe_events_single_event_title_html_before', '<h1 class="' . $title_classes . '">', $event_id );

/**
 * Allows filtering of the single event template title after HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display after the title text.
 * @param string $event_id The ID of the displayed event.
 */
$after = apply_filters( 'tribe_events_single_event_title_html_after', '</h1>', $event_id );

/**
 * Allows filtering of the single event template title HTML.
 *
 * @since 5.8.0
 *
 * @param string $after HTML string to display. Return an empty string to not display the title.
 * @param string $event_id The ID of the displayed event.
 */
$title = apply_filters( 'tribe_events_single_event_title_html', the_title( $before, $after, false ), $event_id );
$cost  = tribe_get_formatted_cost( $event_id );

while ( have_posts() ) :
	the_post();
	?>

<main id="content" <?php post_class( 'site-main' ); ?>>

	<?php if ( apply_filters( 'hello_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>
	<?php endif; ?>

	<div class="page-content">
		<?php the_content(); ?>
		<div class="post-tags">
			<?php the_tags( '<span class="tag-links">' . esc_html__( 'Tagged ', 'hello-elementor' ), null, '</span>' ); ?>
		</div>
		<?php wp_link_pages(); ?>
	</div>

	<?php comments_template(); ?>

</main>

	<?php
endwhile;