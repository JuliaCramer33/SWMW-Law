<?php
/**
 * Result card — for Query Loop / template context (current post).
 *
 * @param array $block Block settings.
 *
 * @package SWMW_Law
 */

$is_preview = ! empty( $is_preview );
$post_id    = get_the_ID();

if ( ! $post_id || get_post_type( $post_id ) !== 'swmw_result' ) {
	if ( $is_preview ) {
		echo '<p><em>' . esc_html__( 'Place this block inside a Query Loop that lists Results, or view on the front end.', 'swmw-law' ) . '</em></p>';
	}
	return;
}

$class_name = 'result-card-block';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( array( 'class' => $class_name ) ) ); ?>>
	<div class="result-card-block__card">
		<?php
		get_template_part(
			'template-parts/content',
			'result-card',
			array(
				'link_category'    => true,
				'description_mode' => 'diagnosis_states',
			)
		);
		?>
	</div>
</div>
