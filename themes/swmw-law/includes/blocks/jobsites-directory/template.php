<?php
/**
 * Jobsites Directory Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */



// Query all cities to get their jobsites
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
        
        // Get jobsites from this city's repeater field
        if (have_rows('jobsites', get_the_ID())) {
            while (have_rows('jobsites', get_the_ID())) {
                the_row();
                $jobsite_name = get_sub_field('jobsite_name');
                if ($jobsite_name) {
                    $all_jobsites[] = $jobsite_name;
                }
            }
        }
    }
}
wp_reset_postdata();

// Sort jobsites alphabetically
sort($all_jobsites, SORT_STRING | SORT_FLAG_CASE);

// Define letter ranges for 6 panels (3 columns x 2 rows)
$letter_ranges = [
    ['# - D', ['#', 'A', 'B', 'C', 'D']],
    ['E - H', ['E', 'F', 'G', 'H']],
    ['I - L', ['I', 'J', 'K', 'L']],
    ['M - P', ['M', 'N', 'O', 'P']],
    ['Q - T', ['Q', 'R', 'S', 'T']],
    ['U - Z', ['U', 'V', 'W', 'X', 'Y', 'Z']]
];

// Group jobsites by letter ranges
$jobsites_by_range = [];
foreach ($letter_ranges as $range) {
    $range_name = $range[0];
    $range_letters = $range[1];
    $jobsites_by_range[$range_name] = [];
    
    foreach ($all_jobsites as $jobsite) {
        $first_letter = strtoupper(substr($jobsite, 0, 1));
        
        // Handle special characters and numbers
        if ($first_letter === '#' && in_array('#', $range_letters)) {
            if (!preg_match('/^[A-Z]/', $jobsite)) {
                $jobsites_by_range[$range_name][] = $jobsite;
            }
        } elseif (in_array($first_letter, $range_letters)) {
            $jobsites_by_range[$range_name][] = $jobsite;
        }
    }
}

// Generate unique ID for this block instance
$block_unique_id = 'jobsites-directory-' . uniqid();

// Debug: Check if we have any jobsites
$total_jobsites = count($all_jobsites);
?>

<div <?php echo wp_kses_post(get_block_wrapper_attributes(['class' => 'jobsites-directory-block'])); ?>>
    <?php if ($total_jobsites > 0): ?>
        <!-- Jobsites Directory Accordion -->
        <div class="jobsites-directory-accordion accordion-block" id="<?php echo esc_attr($block_unique_id); ?>">
            <?php foreach ($jobsites_by_range as $range_name => $jobsites): ?>
                <?php if (!empty($jobsites)): ?>
                    <div class="accordion-panel" data-range="<?php echo esc_attr($range_name); ?>">
                        <div class="accordion-panel-header">
                            <h3 class="accordion-panel-title"><?php echo esc_html($range_name); ?></h3>
                            <span class="accordion-panel-icon"></span>
                        </div>
                        <div class="accordion-panel-content">
                            <div class="accordion-panel-content-inner">
                                <ul class="jobsites-list">
                                    <?php 
                                    // In editor, show only first 5 jobsites per range
                                    $jobsites_to_show = $is_preview ? array_slice($jobsites, 0, 5) : $jobsites;
                                    $total_count = count($jobsites);
                                    
                                    foreach ($jobsites_to_show as $jobsite): ?>
                                        <li class="jobsite-item">
                                            <div class="jobsite-card">
                                                <p class="jobsite-name"><?php echo esc_html($jobsite); ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                    
                                    <?php if ($is_preview && $total_count > 5): ?>
                                        <li class="jobsite-item">
                                            <div class="jobsite-card">
                                                <p class="jobsite-name" style="color: #666; font-style: italic;">
                                                    ... and <?php echo esc_html($total_count - 5); ?> more jobsites
                                                </p>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- No Jobsites Found -->
        <div style="background: #fff3cd; padding: 20px; border: 1px solid #ffeaa7; border-radius: 4px;">
            <h3>No Jobsites Found</h3>
            <p>No jobsites were found in any city post types. Please check:</p>
            <ul>
                <li>That city post types exist</li>
                <li>That cities have jobsites in their repeater fields</li>
                <li>That the jobsites have names</li>
            </ul>
        </div>
    <?php endif; ?>
</div> 
