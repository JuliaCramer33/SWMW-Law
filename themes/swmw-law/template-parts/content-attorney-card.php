<?php
/**
 * Template part for displaying an Attorney card.
 *
 * @package SWMW_Law
 */

$positions = get_the_terms( get_the_ID(), 'attorney_position' );
$position = ($positions && !is_wp_error($positions)) ? $positions[0]->name : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('attorney-card-item'); ?>>
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
