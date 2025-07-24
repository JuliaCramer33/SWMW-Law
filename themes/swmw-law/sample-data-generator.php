<?php
/**
 * Unified City Import & Delete Script
 * - Creates missing state terms
 * - Adds/updates cities with ACF fields
 * - Handles jobsite splitting on newlines only, with suffix rejoining (e.g., "Inc.")
 * - Supports batching via ?action=import&page=1
 */

if (!defined('ABSPATH')) {
    require_once dirname(__FILE__) . '/../../../wp-load.php';
}

$batch_size = 50;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start_index = ($page - 1) * $batch_size;

echo '<h1>City Data Manager</h1>';
echo '<p><a href="?action=import&page=1" style="background:#0073aa;color:white;padding:10px 20px;border-radius:4px;text-decoration:none;margin-right:10px;">✅ Import Cities & Jobsites (with States)</a>';
echo '<a href="?action=delete" style="background:#aa0000;color:white;padding:10px 20px;border-radius:4px;text-decoration:none;">🗑 Delete All Cities</a></p>';

// ----------------------------
// DELETE CITIES
// ----------------------------
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

// ----------------------------
// IMPORT CITIES + JOBSITES + STATES
// ----------------------------
if (isset($_GET['action']) && $_GET['action'] === 'import') {
    echo "<h2>Importing Cities and Jobsites – Page $page</h2>";

    $csv_file = get_template_directory() . '/data/jobsites.csv';
    if (!file_exists($csv_file)) {
        die('<p style="color:red;">CSV file not found at: ' . esc_html($csv_file) . '</p>');
    }

    $handle = fopen($csv_file, 'r');
    $headers = fgetcsv($handle);

    // Read all rows
    $rows = [];
    while (($row = fgetcsv($handle)) !== false) {
        $rows[] = array_combine($headers, $row);
    }
    fclose($handle);

    $total_rows = count($rows);
    $batch = array_slice($rows, $start_index, $batch_size);

    $created = 0;
    $updated = 0;
    $skipped = 0;
    $errors = [];

    foreach ($batch as $data) {
        $state_raw = (string) $data['State'];
        $state = ucwords(strtolower(trim($state_raw)));
        $city = trim((string) $data['City']);
        $jobsite_raw = isset($data['Job Sites']) ? (string) $data['Job Sites'] : '';

        if (empty($city)) {
            $skipped++;
            $errors[] = "⚠️ Skipped row with empty city (State: $state)";
            continue;
        }

        // 1. Create or get state term
        $taxonomy = 'state';
        $state_term = get_term_by('name', $state, $taxonomy);
        if (!$state_term) {
            $state_term = wp_insert_term($state, $taxonomy);
            if (is_wp_error($state_term)) {
                $errors[] = "❌ Failed to create state term: '$state'";
                continue;
            } else {
                $state_term = get_term_by('name', $state, $taxonomy);
                echo "<p>✅ Created state: $state</p>";
            }
        }

        // 2. Check for existing city
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

        // 3. Create city
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

        // 4. Assign taxonomy state
        update_field('state', $state_term->term_id, $city_id);

        // 5. Clean jobsite list (split by newline and rejoin suffixes)
        $jobsite_lines = preg_split('/\r\n|\r|\n/', $jobsite_raw);
        $jobsite_lines = array_map('trim', $jobsite_lines);
        $jobsite_lines = array_filter($jobsite_lines);

        $repeater = [];
        $buffer = '';

        foreach ($jobsite_lines as $line) {
            if (preg_match('/^(inc\.?|corporation|corp\.?|l\.?l\.?c\.?)$/i', $line)) {
                $buffer .= ' ' . $line;
            } else {
                if ($buffer) {
                    $repeater[] = ['jobsite_name' => trim($buffer)];
                }
                $buffer = $line;
            }
        }
        if ($buffer) {
            $repeater[] = ['jobsite_name' => trim($buffer)];
        }

        update_field('jobsites', $repeater, $city_id);
    }

    // Completion Summary
    echo "<h3>✅ Page $page Import Complete</h3>";
    echo "<p><strong>Created:</strong> $created</p>";
    echo "<p><strong>Updated:</strong> $updated</p>";
    echo "<p><strong>Skipped:</strong> $skipped</p>";

    if (!empty($errors)) {
        echo '<h4>Errors & Warnings:</h4><ul>';
        foreach ($errors as $err) {
            echo '<li>' . esc_html($err) . '</li>';
        }
        echo '</ul>';
    }

    // Pagination logic
    $next_page = $start_index + $batch_size < $total_rows ? $page + 1 : null;
    if ($next_page) {
        $next_url = esc_url(add_query_arg(['action' => 'import', 'page' => $next_page]));
        echo "<p><a href=\"$next_url\" style=\"background:#46b450;color:white;padding:10px 20px;border-radius:4px;text-decoration:none;\">➡️ Next Page ($next_page)</a></p>";
    } else {
        echo "<p><strong>🎉 All rows imported!</strong></p>";
    }
}
?>
