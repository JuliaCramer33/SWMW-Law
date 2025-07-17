<?php
/**
 * Sample Data Generator for Jobsites by City Block
 * 
 * This script creates sample cities with jobsites for testing.
 * Run this file directly in your browser or via WP-CLI.
 * 
 * @package SWMW_Law
 */

// Prevent direct access if not in WordPress
if (!defined('ABSPATH')) {
    // If running directly, bootstrap WordPress
    $wp_load = dirname(__FILE__) . '/../../../wp-load.php';
    if (file_exists($wp_load)) {
        require_once($wp_load);
    } else {
        die('WordPress not found. Please run this from your theme directory.');
    }
}

/**
 * Generate sample data
 */
function generate_sample_data() {
    echo '<h1>Generating Sample Data for Jobsites by City Block</h1>';
    
    $created_count = 0;
    $errors = array();
    
    // Sample data - Connecticut
    $connecticut_cities = array(
     'Bridgeport' => array('FEC Bridgeport Terminal', 'Bridgeport Power Plant', 'General Electric Bridgeport', 'Remington Arms Company', 'Bridgeport Brass Company'),
   'Hartford' => array('Hartford Steam Boiler', 'Colt Manufacturing', 'Hartford Hospital', 'Connecticut General Life Insurance', 'Hartford Electric Light Company'),
    'New Haven' => array('Yale University Power Plant', 'New Haven Power Company', 'Winchester Repeating Arms', 'New Haven Railroad Shops', 'Southern Connecticut Gas Company'),
   'Stamford' => array('Stamford Power Plant', 'Pitney Bowes Manufacturing', 'Stamford Hospital', 'Connecticut Light & Power', 'Stamford Gas Company'),    'Waterbury' => array('Waterbury Brass Company', 'Chase Brass & Copper', 'Waterbury Hospital', 'Waterbury Power Company', 'American Brass Company'),
        'Danbury' => array('Danbury Hospital', 'Danbury Power Plant', 'Hat Corporation of America', 'Danbury Railway Museum', 'Danbury Gas Company'),
        'Norwalk' => array('Norwalk Power Plant', 'Norwalk Hospital', 'Norwalk Gas Company', 'Norwalk Railway Station', 'Norwalk Manufacturing'),    'Greenwich' => array('Greenwich Hospital', 'Greenwich Power Plant', 'Greenwich Gas Company', 'Greenwich Railway Station', 'Greenwich Manufacturing'),
    'Fairfield' => array('Fairfield University Power Plant', 'Fairfield Hospital', 'Fairfield Power Company', 'Fairfield Gas Company', 'Fairfield Manufacturing'),
   'Westport' => array('Westport Power Plant', 'Westport Hospital', 'Westport Gas Company', 'Westport Railway Station', 'Westport Manufacturing')    );
    
    // Sample data - Florida
    $florida_cities = array(
        'Hialeah' => array('FEC Hialeah TT', 'Miami Mills Inc.', 'T & H Insulation, Inc.', 'Holiday Inn', 'Seaboard Airline Railroad Company'),
        'Holiday' => array('Anclote Power Plant'),    'Hollywood' => array('Diplomat Hotel', 'Hollywood Memorial Hospital', 'Hollywood Florida Water & Sewage', 'Memorial Hospital'),
    'Homestead' => array('Everglades National Park', 'Turkey Point Powerhouse', 'Homestead Air Force Base', 'U S Air Force'),
        'Miami' => array('Miami Power Plant', 'Miami Hospital', 'Miami Gas Company', 'Miami Railway Station', 'Miami Manufacturing'),
        'Tampa' => array('Tampa Power Plant', 'Tampa Hospital', 'Tampa Gas Company', 'Tampa Railway Station', 'Tampa Manufacturing'),
        'Orlando' => array('Orlando Power Plant', 'Orlando Hospital', 'Orlando Gas Company', 'Orlando Railway Station', 'Orlando Manufacturing'),
       'Jacksonville' => array('Jacksonville Power Plant', 'Jacksonville Hospital', 'Jacksonville Gas Company', 'Jacksonville Railway Station', 'Jacksonville Manufacturing'),
       'Fort Lauderdale' => array('Fort Lauderdale Power Plant', 'Fort Lauderdale Hospital', 'Fort Lauderdale Gas Company', 'Fort Lauderdale Railway Station', 'Fort Lauderdale Manufacturing'),
       'West Palm Beach' => array('West Palm Beach Power Plant', 'West Palm Beach Hospital', 'West Palm Beach Gas Company', 'West Palm Beach Railway Station', 'West Palm Beach Manufacturing')    );
    
    // Process Connecticut
    echo '<h2>Processing State: Connecticut</h2>';
    $state_term = term_exists('Connecticut', 'state');
    if (!$state_term) {
        $state_term = wp_insert_term('Connecticut', 'state');
        if (is_wp_error($state_term)) {
            $errors[] = 'Failed to create state: Connecticut';
        }
    }
    $state_term_id = is_array($state_term) ? $state_term['term_id'] : $state_term;
    echo '<p>✓ State Connecticut ready (ID: ' . esc_html($state_term_id) . ')</p>';
    
    foreach ($connecticut_cities as $city_name => $jobsites) {
        echo '<h3>Creating City: ' . esc_html($city_name) . '</h3>';
        
        // Check if city already exists
        $existing_city = get_page_by_title($city_name, OBJECT, 'city');
        if ($existing_city) {
            echo '<p>⚠ City ' . esc_html($city_name) . ' already exists, skipping...</p>';
            continue;
        }
        
        // Create city post
        $city_post_data = array(
           'post_title'   => $city_name,
            'post_content'  => 'Sample city data for ' . $city_name . ' with known asbestos exposure sites.',
            'post_status'   => 'publish',
            'post_type'     => 'city',
            'post_author'   => 1
        );
        
        $city_id = wp_insert_post($city_post_data);
        
        if (is_wp_error($city_id)) {
            $errors[] = 'Failed to create city: ' . $city_name;
            continue;
        }
        
        // Set state taxonomy
        wp_set_object_terms($city_id, $state_term_id, 'state');
        
        // Create jobsites repeater data
        $jobsites_data = array();
        foreach ($jobsites as $jobsite_name) {
            $jobsites_data[] = array(
               'jobsite_name' => $jobsite_name,
               'jobsite_address' => 'Sample address for ' . $jobsite_name,
      'jobsite_description' => 'Known asbestos exposure site in ' . $city_name . '.'
            );
        }
        
        // Save jobsites to ACF field
        update_field('jobsites', $jobsites_data, $city_id);
        
        echo '<p>✓ Created city ' . esc_html($city_name) . ' with ' . count($jobsites) . ' jobsites</p>';
        $created_count++;
    }
    
    // Process Florida
    echo '<h2>Processing State: Florida</h2>';
    $state_term = term_exists('Florida', 'state');
    if (!$state_term) {
        $state_term = wp_insert_term('Florida', 'state');
        if (is_wp_error($state_term)) {
            $errors[] = 'Failed to create state: Florida';
        }
    }
    $state_term_id = is_array($state_term) ? $state_term['term_id'] : $state_term;
    echo '<p>✓ State Florida ready (ID: ' . esc_html($state_term_id) . ')</p>';
    
    foreach ($florida_cities as $city_name => $jobsites) {
        echo '<h3>Creating City: ' . esc_html($city_name) . '</h3>';
        
        // Check if city already exists
        $existing_city = get_page_by_title($city_name, OBJECT, 'city');
        if ($existing_city) {
            echo '<p>⚠ City ' . esc_html($city_name) . ' already exists, skipping...</p>';
            continue;
        }
        
        // Create city post
        $city_post_data = array(
           'post_title'   => $city_name,
            'post_content'  => 'Sample city data for ' . $city_name . ' with known asbestos exposure sites.',
            'post_status'   => 'publish',
            'post_type'     => 'city',
            'post_author'   => 1
        );
        
        $city_id = wp_insert_post($city_post_data);
        
        if (is_wp_error($city_id)) {
            $errors[] = 'Failed to create city: ' . $city_name;
            continue;
        }
        
        // Set state taxonomy
        wp_set_object_terms($city_id, $state_term_id, 'state');
        
        // Create jobsites repeater data
        $jobsites_data = array();
        foreach ($jobsites as $jobsite_name) {
            $jobsites_data[] = array(
               'jobsite_name' => $jobsite_name,
               'jobsite_address' => 'Sample address for ' . $jobsite_name,
      'jobsite_description' => 'Known asbestos exposure site in ' . $city_name . '.'
            );
        }
        
        // Save jobsites to ACF field
        update_field('jobsites', $jobsites_data, $city_id);
        
        echo '<p>✓ Created city ' . esc_html($city_name) . ' with ' . count($jobsites) . ' jobsites</p>';
        $created_count++;
    }
    
    echo '<h2>Summary</h2>';
    echo '<p>✅ Successfully created ' . esc_html($created_count) . ' cities</p>';
    
    if (!empty($errors)) {
        echo '<h3>Errors:</h3>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>❌ ' . esc_html($error) . '</li>';
        }
        echo '</ul>';
    }
    
    echo '<p><strong>Sample data generation complete!</strong></p>';
    echo '<p>You can now test your Jobsites by City block in the editor.</p>';
}

// Run the generator
if (isset($_GET['generate']) || (defined('WP_CLI') && WP_CLI)) {
    generate_sample_data();
} else {
    echo '<h1>Sample Data Generator</h1>';
    echo '<p>This script will create sample cities with jobsites for testing the Jobsites by City block.</p>';
    echo '<p><a href="?generate=1" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px;">Generate Sample Data</a></p>';
    echo '<p><strong>Note:</strong> This will create sample data. Only run this in a development environment.</p>';
}
?> 
