<?php
/**
 * Jobsites by City Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Get block data
$block_id = $block['id'] ?? 'jobsites-' . uniqid();
$block_title = get_field('title') ?: 'Jobsites by City';
$state_filter = get_field('state_filter'); // Optional: filter by specific state

// Query cities
$args = array(
  'post_type' => 'city',
  'posts_per_page' => -1,
    'post_status' => 'publish',
'orderby' => 'title',
    'order' => 'ASC',
);

// Add state filter if specified
if ($state_filter) {
    $args['tax_query'] = array(
        array(
         'taxonomy' => 'state',
          'field' => 'term_id',
           'terms' => $state_filter,
        ),
    );
}

$cities_query = new WP_Query($args);

// Group cities by first letter
$cities_by_letter = array();
$letters_with_cities = array();

if ($cities_query->have_posts()) {
    while ($cities_query->have_posts()) {
        $cities_query->the_post();
        $city_title = get_the_title();
        $first_letter = strtoupper(substr($city_title, 0, 1));
        
        // Only include letters A-Z
        if (preg_match('/^[A-Z]$/', $first_letter)) {
            if (!isset($cities_by_letter[$first_letter])) {
                $cities_by_letter[$first_letter] = array();
                $letters_with_cities[] = $first_letter;
            }
            
            $cities_by_letter[$first_letter][] = array(
                'id' => get_the_ID(),
              'title' => $city_title,
               'jobsites' => get_field('jobsites'),
            );
        }
    }
}
wp_reset_postdata();

// Sort letters
sort($letters_with_cities);

// Generate unique ID for this block instance
$block_unique_id = 'jobsites-' . uniqid();

?>
<div <?php echo wp_kses_post(get_block_wrapper_attributes(['class' => 'jobsites-by-city-block container'])); ?>>
    <!-- A-Z Filter Bar (Desktop) -->
    <div class="jobsites-filter-bar">
        <span class="filter-label">Search by City:</span>
        <div class="filter-letters">
            <?php foreach (range('A', 'Z') as $letter): ?>
                <?php $has_cities = in_array($letter, $letters_with_cities); ?>
                <button 
                    class="filter-letter <?php echo $has_cities ? 'has-cities' : 'no-cities'; ?><?php echo $letter === 'H' ? 'active' : ''; ?>"
                    data-letter="<?php echo esc_attr($letter); ?>"
                    <?php echo !$has_cities ? 'disabled' : ''; ?>
                    aria-label="<?php echo esc_attr(sprintf('Show cities starting with %s', $letter)); ?>"
                >
                    <?php echo esc_html($letter); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Skip to Dropdown (Mobile) -->
    <div class="jobsites-skip-to">
        <label for="skip-to-<?php echo esc_attr($block_unique_id); ?>" class="skip-to-label">Skip to:</label>
        <select id="skip-to-<?php echo esc_attr($block_unique_id); ?>" class="skip-to-select">
            <option value="">Select a letter...</option>
            <?php foreach ($letters_with_cities as $letter): ?>
                <option value="<?php echo esc_attr($letter); ?>"><?php echo esc_html($letter); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Cities Accordion -->
    <div class="jobsites-accordion accordion-block" id="<?php echo esc_attr($block_unique_id); ?>">
        <?php foreach ($letters_with_cities as $letter): ?>
            <?php $cities = $cities_by_letter[$letter]; ?>
            <div class="accordion-panel <?php echo $letter === 'H' ? 'is-open' : ''; ?>" data-letter="<?php echo esc_attr($letter); ?>">
                <div class="accordion-panel-header">
                    <h3 class="accordion-panel-title"><?php echo esc_html($letter); ?></h3>
                    <span class="accordion-panel-icon"></span>
                </div>
                <div class="accordion-panel-content">
                    <div class="accordion-panel-content-inner">
                        <?php foreach ($cities as $city): ?>
                            <div class="city-group">
                                <h4 class="city-name"><?php echo esc_html($city['title']); ?>:</h4>
                                <?php 
                                // Use ACF's have_rows() to loop through repeater field
                                if (have_rows('jobsites', $city['id'])): ?>
                                    <ul class="jobsites-list">
                                        <?php while (have_rows('jobsites', $city['id'])): the_row(); ?>
                                            <li class="jobsite-item">
                                                <div class="jobsite-card">
                                                    <p class="jobsite-name"><?php echo esc_html(get_sub_field('jobsite_name')); ?></p>
                                                </div>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="no-jobsites">No jobsites listed for this city.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
</div>

<!-- Load block-specific JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof JobsitesByCityBlock !== 'undefined') {
        new JobsitesByCityBlock('<?php echo esc_js($block_unique_id); ?>');
    }
});
</script> 
