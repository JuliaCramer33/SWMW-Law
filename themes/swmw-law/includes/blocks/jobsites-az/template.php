<?php
/**
 * Jobsites A-Z Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Query all cities
$args = array(
    'post_type' => 'city',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
);

$cities_query = new WP_Query($args);

// Get all jobsites from all cities
$all_jobsites = [];

if ($cities_query->have_posts()) {
    while ($cities_query->have_posts()) {
        $cities_query->the_post();
        $city_title = get_the_title();
        
        // Get jobsites from this city
        if (have_rows('jobsites', get_the_ID())) {
            while (have_rows('jobsites', get_the_ID())) {
                the_row();
                $jobsite_name = get_sub_field('jobsite_name');
                if ($jobsite_name) {
                    $all_jobsites[] = [
                        'name' => $jobsite_name,
                        'city' => $city_title
                    ];
                }
            }
        }
    }
}
wp_reset_postdata();

// Sort jobsites alphabetically by name
usort($all_jobsites, function($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});

// Group jobsites by first letter
$jobsites_by_letter = [];
$letters_with_jobsites = [];

foreach ($all_jobsites as $jobsite) {
    $first_letter = strtoupper(substr($jobsite['name'], 0, 1));
    // Only include letters A-Z
    if (preg_match('/^[A-Z]$/', $first_letter)) {
        if (!isset($jobsites_by_letter[$first_letter])) {
            $jobsites_by_letter[$first_letter] = [];
            $letters_with_jobsites[] = $first_letter;
        }
        $jobsites_by_letter[$first_letter][] = $jobsite;
    }
}

// Sort letters
sort($letters_with_jobsites);

// Generate unique ID for this block instance
$block_unique_id = 'jobsites-az-' . uniqid();
?>

<div <?php echo wp_kses_post(get_block_wrapper_attributes(['class' => 'jobsites-by-city-block container'])); ?>>
    <!-- A-Z Filter Bar (Desktop) -->
    <div class="jobsites-filter-bar">
        <span class="filter-label">Search by Jobsites:</span>
        <div class="filter-letters">
            <?php foreach (range('A', 'Z') as $letter): ?>
                <?php $has_jobsites = in_array($letter, $letters_with_jobsites); ?>
                <button 
                    class="filter-letter <?php echo $has_jobsites ? 'has-cities' : 'no-cities'; ?>"
                    data-letter="<?php echo esc_attr($letter); ?>"
                    <?php echo !$has_jobsites ? 'disabled' : ''; ?>
                    aria-label="<?php echo esc_attr(sprintf('Show jobsites starting with %s', $letter)); ?>"
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
            <?php foreach ($letters_with_jobsites as $letter): ?>
                <option value="<?php echo esc_attr($letter); ?>"><?php echo esc_html($letter); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <!-- Jobsites Accordion -->
    <div class="jobsites-accordion accordion-block" id="<?php echo esc_attr($block_unique_id); ?>">
        <?php foreach ($letters_with_jobsites as $letter): ?>
            <?php $jobsites = $jobsites_by_letter[$letter]; ?>
            <div class="accordion-panel" data-letter="<?php echo esc_attr($letter); ?>">
                <div class="accordion-panel-header">
                    <h3 class="accordion-panel-title"><?php echo esc_html($letter); ?></h3>
                    <span class="accordion-panel-icon"></span>
                </div>
                <div class="accordion-panel-content">
                    <div class="accordion-panel-content-inner">
                        <ul class="jobsites-list">
                            <?php foreach ($jobsites as $jobsite): ?>
                                <li class="jobsite-item">
                                    <div class="jobsite-card">
                                        <p class="jobsite-name"><?php echo esc_html($jobsite['name']); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div> 
