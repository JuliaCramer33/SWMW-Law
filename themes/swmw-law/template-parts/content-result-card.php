<?php
/**
 * Single result card markup (archive grid, featured strip, taxonomy, S&F-ready).
 *
 * Expects the main loop or a query loop to have set up the current post (`the_post()`).
 *
 * @package SWMW_Law
 *
 * Args (via {@see get_template_part()} third parameter):
 * - link_category bool Link the category label to the term archive when true. Default false.
 * - show_secondary bool Show result_secondary_description when present. Default true.
 * - description_mode string "secondary" (default) or "diagnosis_states" — latter prints Diagnosis (State, …) instead.
 */

defined( 'ABSPATH' ) || exit;

$link_category     = isset( $link_category ) ? (bool) $link_category : false;
$show_secondary    = isset( $show_secondary ) ? (bool) $show_secondary : true;
$description_mode  = isset( $description_mode ) ? (string) $description_mode : 'secondary';
$description_mode  = in_array( $description_mode, array( 'secondary', 'diagnosis_states' ), true ) ? $description_mode : 'secondary';

$case_types = get_the_terms( get_the_ID(), 'swmw_result_category' );
$case_type  = ( ! empty( $case_types ) && ! is_wp_error( $case_types ) ) ? $case_types[0] : null;
$case_name  = $case_type ? $case_type->name : '';
$case_link  = $case_type ? get_term_link( $case_type ) : '';
?>
<div class="result-item-inner">
	<?php if ( $case_name ) : ?>
		<?php if ( $link_category && $case_link && ! is_wp_error( $case_link ) ) : ?>
			<a class="result-category" href="<?php echo esc_url( $case_link ); ?>"><?php echo esc_html( $case_name ); ?></a>
		<?php else : ?>
			<span class="result-category"><?php echo esc_html( $case_name ); ?></span>
		<?php endif; ?>
	<?php endif; ?>

	<h3 class="result-amount"><?php echo esc_html( \SWMW_Law\swmw_law_get_formatted_amount() ); ?></h3>
	<?php $heading_occ = \SWMW_Law\swmw_law_format_result_heading_occupation(); ?>
	<?php if ( $heading_occ ) : ?>
		<h4 class="result-title"><?php echo esc_html( $heading_occ ); ?></h4>
	<?php endif; ?>
	<?php if ( 'diagnosis_states' === $description_mode ) : ?>
		<?php
		$caption = \SWMW_Law\swmw_law_format_result_diagnosis_states_caption( get_the_ID() );
		?>
		<?php if ( $caption !== '' ) : ?>
			<p class="result-description"><?php echo esc_html( $caption ); ?></p>
		<?php endif; ?>
	<?php elseif ( $show_secondary ) : ?>
		<?php
		$post_id   = get_the_ID();
		$secondary = function_exists( 'get_field' ) ? (string) get_field( 'result_secondary_description', $post_id ) : '';
		if ( '' === trim( $secondary ) ) {
			$secondary = (string) get_post_meta( $post_id, 'result_secondary_description', true );
		}
		?>
		<?php if ( $secondary ) : ?>
			<p class="result-description"><?php echo esc_html( $secondary ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div>
