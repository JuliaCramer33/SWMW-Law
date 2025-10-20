<?php
/**
 * Template part for displaying an Attorney card.
 *
 * @package SWMW_Law
 */

$positions = get_the_terms( get_the_ID(), 'attorney_position' );
$position = ($positions && !is_wp_error($positions)) ? $positions[0]->name : '';
$is_member = has_term( 'member', 'attorney_position', get_the_ID() ) || has_term( 'members', 'attorney_position', get_the_ID() );
$start_date = get_field( 'attorney_start_date', get_the_ID() ); // Ymd per ACF field
$start_sort = $start_date ? preg_replace( '/[^0-9]/', '', (string) $start_date ) : '99999999';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('attorney-card-item'); ?> data-is-member="<?php echo esc_attr( $is_member ? '0' : '1' ); ?>" data-start-date="<?php echo esc_attr( $start_sort ); ?>">
	<div class="attorney-card-image">
		<a href="<?php the_permalink(); ?>" rel="bookmark">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail('medium_large'); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/avatar-placeholder.svg' ); ?>" alt="<?php the_title_attribute(); ?> placeholder" />
			<?php endif; ?>
		</a>
	</div>

	<div class="attorney-card-content">
		<h3 class="attorney-card-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h3>
		<?php if ( $position ) : ?>
			<p class="attorney-card-position"><?php echo esc_html( $position ); ?></p>
		<?php endif; ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> --> 
