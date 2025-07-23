<?php
/**
 * Import or Delete Cities and Jobsites (ACF Dropdown Mode)
 */

if (!defined('ABSPATH')) {
    require_once dirname(__FILE__) . '/../../../wp-load.php';
}

echo '<h1>City Data Manager</h1>';
echo '<p><a href="?action=import" style="background:#0073aa;color:white;padding:10px 20px;border-radius:4px;text-decoration:none;margin-right:10px;">✅ Import Cities & Jobsites</a>';
echo '<a href="?action=delete" style="background:#aa0000;color:white;padding:10px 20px;border-radius:4px;text-decoration:none;">🗑 Delete All Cities</a></p>';

// -------------------
// DELETE CITIES
// -------------------
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    echo '<h2>Deleting all city posts...</h2>';

    $cities = get_posts([
        'post_type' => 'city',
        'numberposts' => -1,
        'post_status' => 'any',
    ]);

    $count = 0;
    foreach ($cities as $city) {
        wp_delete_post($city->ID, true);
        echo "<p>🗑 Deleted city: {$city->post_title}</p>";
        $count++;
    }

    echo "<p><strong>✅ Deleted $count cities.</strong></p>";
    return;
}

// -------------------
// IMPORT CITIES + JOBSITES
// -------------------
if (isset($_GET['action']) && $_GET['action'] === 'import') {
    echo '<h2>Importing Cities and Jobsites...</h2>';

    $csv_file = get_template_directory() . '/data/jobsites.csv';
    if (!file_exists($csv_file)) {
        die('<p style="color:red;">CSV file not found at: ' . esc_html($csv_file) . '</p>');
    }

    $handle = fopen($csv_file, 'r');
    $headers = fgetcsv($handle);
    $created = 0;
    $updated = 0;
    $errors = [];

    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($headers, $row);

        $state_raw = (string) $data['State'];
        $state = ucwords(strtolower(trim($state_raw)));
        $city = trim((string) $data['City']);
        $jobsite_list = array_map('trim', explode(',', $data['Job Sites']));

        // Lookup state taxonomy term
        $state_term = get_term_by('name', $state, 'state');
        if (!$state_term) {
            $errors[] = "❌ State term not found: '$state'";
            continue;
        }

        // Find existing city in same state
        $existing_posts = get_posts([
            'post_type' => 'city',
            'title' => $city,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        $city_id = null;
        foreach ($existing_posts as $post) {
            $acf_state = get_field('state', $post->ID);
            $acf_state_id = is_array($acf_state) ? $acf_state[0]->term_id ?? $acf_state[0] : $acf_state;
            if ((int)$acf_state_id === (int)$state_term->term_id) {
                $city_id = $post->ID;
                break;
            }
        }

        // Create city if not found
        if (!$city_id) {
            $city_id = wp_insert_post([
                'post_title' => $city,
                'post_type' => 'city',
                'post_status' => 'publish',
            ]);
            if (is_wp_error($city_id)) {
                $errors[] = "❌ Failed to create city: $city";
                continue;
            }
            $created++;
            echo "<p>✅ Created city: $city ($state)</p>";
        } else {
            $updated++;
            echo "<p>↻ Updated city: $city ($state)</p>";
        }

        // Set ACF taxonomy field (ACF dropdown version)
        update_field('state', $state_term->term_id, $city_id);

        // Repeater for jobsites
        $repeater = [];
        foreach ($jobsite_list as $jobsite) {
            if ($jobsite) {
                $repeater[] = ['jobsite_name' => $jobsite];
            }
        }
        update_field('jobsites', $repeater, $city_id);
    }

    fclose($handle);

    echo "<h3>✅ Import Complete</h3>";
    echo "<p><strong>Created:</strong> $created</p>";
    echo "<p><strong>Updated:</strong> $updated</p>";

    if (!empty($errors)) {
        echo '<h4>Errors:</h4><ul>';
        foreach ($errors as $err) {
            echo '<li>' . esc_html($err) . '</li>';
        }
        echo '</ul>';
    }
}
?>
