<?php
/**
 * Batched City Import & Delete Script (stable)
 * - Exact title + state match (prevents duplicates like "Bath, Pennsylvania")
 * - Streams CSV and processes by offset (prevents re-doing first page)
 * - Assigns taxonomy state AND ACF taxonomy field
 * - Jobsites: newline-only + rejoin orphaned suffixes ("Inc.", "Corp", "LLC")
 *   Run: /wp-content/themes/your-theme/sample-data-generator.php?action=import&offset=0
 */

if (!defined('ABSPATH')) {
    require_once dirname(__FILE__) . '/../../../wp-load.php';
}

$batch_size = isset($_GET['batch_size']) ? max(1, intval($_GET['batch_size'])) : 50;
$offset     = isset($_GET['offset']) ? max(0, intval($_GET['offset'])) : 0;
$action     = isset($_GET['action']) ? $_GET['action'] : '';

echo '<h1>City Data Manager</h1>';
echo '<p>
  <a href="?action=import&offset=0&batch_size='.$batch_size.'" style="background:#0073aa;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;margin-right:10px;">✅ Import (start)</a>
  <a href="?action=delete" style="background:#aa0000;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;">🗑 Delete All Cities</a>
</p>';

function normalize_state_name($state_raw) {
    $s = trim((string)$state_raw);
    // Handle accidental numerics or lower/upper noise
    $s = ucwords(strtolower($s));
    return $s;
}

// ---- DELETE ALL CITIES ----
if ($action === 'delete') {
    echo '<h2>Deleting all city posts…</h2>';
    $cities = get_posts([
        'post_type'   => 'city',
        'numberposts' => -1,
        'post_status' => 'any',
    ]);
    $count = 0;
    foreach ($cities as $city) {
        wp_delete_post($city->ID, true);
        echo '<p>🗑 Deleted city: ' . esc_html($city->post_title) . '</p>';
        $count++;
    }
    echo '<p><strong>✅ Deleted '.$count.' cities.</strong></p>';
    return;
}

// ---- IMPORT ----
if ($action === 'import') {
    $csv_file = get_template_directory() . '/data/jobsites.csv';
    if (!file_exists($csv_file)) {
        die('<p style="color:red;">CSV file not found at: ' . esc_html($csv_file) . '</p>');
    }

    echo '<h2>Importing rows ' . $offset . ' → ' . ($offset + $batch_size - 1) . '</h2>';

    $handle = fopen($csv_file, 'r');
    if (!$handle) {
        die('<p style="color:red;">Failed to open CSV.</p>');
    }

    // Read headers
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        die('<p style="color:red;">CSV appears to be empty or headers are missing.</p>');
    }
    // Map headers to keys
    $headers = array_map('trim', $headers);

    // Stream rows; only process rows in the window [offset, offset + batch_size)
    $row_index = 0;           // data rows (after header)
    $processed = 0;
    $created = 0; $updated = 0; $skipped = 0;
    $errors = [];
    $total_rows = 0;          // we’ll count as we go
    $window_start = $offset;
    $window_end   = $offset + $batch_size - 1;

    // First pass: we can also compute total rows by scanning once
    // But to keep memory low we’ll process and count in a single pass
    $data_rows = [];
    while (($row = fgetcsv($handle)) !== false) {
        $total_rows++;
        // Only collect rows within the current window
        if ($row_index >= $window_start && $row_index <= $window_end) {
            $data_rows[] = array_combine($headers, $row);
        }
        $row_index++;
        if ($row_index > $window_end && $processed >= $batch_size) {
            // We’ve collected enough for this batch
            break;
        }
    }
    fclose($handle);

    // Helper: ensure a WP_Term (state) exists and return it
    function ensure_state_term($state_name) {
        $taxonomy = 'state';
        $term = get_term_by('name', $state_name, $taxonomy);
        if (!$term) {
            $inserted = wp_insert_term($state_name, $taxonomy);
            if (is_wp_error($inserted)) {
                return $inserted; // pass error upward
            }
            $term = get_term_by('name', $state_name, $taxonomy);
            if ($term) {
                echo '<p>✅ Created state: ' . esc_html($state_name) . '</p>';
            }
        }
        return $term;
    }

    // Helper: find an existing city that matches EXACT title and has the SAME state term assigned
    function find_city_by_title_and_state($city_title, $state_term_id) {
        // Use exact title match
        $existing = get_page_by_title($city_title, OBJECT, 'city');
        if (!$existing) {
            return null;
        }
        // Verify its assigned state taxonomy term
        $terms = wp_get_object_terms($existing->ID, 'state', ['fields' => 'ids']);
        if (!is_wp_error($terms) && !empty($terms)) {
            if (in_array((int)$state_term_id, array_map('intval', $terms), true)) {
                return $existing->ID;
            }
        }
        // If ACF field carries it (for sites relying on ACF taxonomy field), we check that too
        $acf_state = function_exists('get_field') ? get_field('state', $existing->ID) : null;
        if ($acf_state) {
            $acf_state_id = is_array($acf_state)
                ? (isset($acf_state[0]->term_id) ? $acf_state[0]->term_id : (isset($acf_state[0]) ? $acf_state[0] : 0))
                : $acf_state;
            if ((int)$acf_state_id === (int)$state_term_id) {
                return $existing->ID;
            }
        }
        return null; // same title, but different state → treat as new city
    }

    foreach ($data_rows as $data) {
        $processed++;
        $state = normalize_state_name($data['State'] ?? '');
        $city  = trim((string)($data['City'] ?? ''));
        $jobsite_raw = (string)($data['Job Sites'] ?? '');

        if ($city === '') {
            $skipped++;
            $errors[] = "⚠️ Skipped row with empty city (State: {$state})";
            continue;
        }

        // Ensure state term exists
        $state_term = ensure_state_term($state);
        if (is_wp_error($state_term) || !$state_term) {
            $skipped++;
            $errors[] = "❌ Failed to create/find state term: '{$state}'";
            continue;
        }
        $state_term_id = is_object($state_term) ? $state_term->term_id : (int)$state_term;

        // Find or create city by exact title + state term
        $city_id = find_city_by_title_and_state($city, $state_term_id);

        if (!$city_id) {
            $city_id = wp_insert_post([
                'post_title'  => $city,
                'post_type'   => 'city',
                'post_status' => 'publish',
            ]);
            if (is_wp_error($city_id)) {
                $skipped++;
                $errors[] = "❌ Failed to create city: {$city}";
                continue;
            }
            $created++;
            echo '<p>✅ Created city: ' . esc_html($city) . ' (' . esc_html($state) . ')</p>';
        } else {
            $updated++;
            echo '<p>↻ Updated city: ' . esc_html($city) . ' (' . esc_html($state) . ')</p>';
        }

        // Assign taxonomy term directly (and ACF field for the dropdown)
        wp_set_object_terms($city_id, [$state_term_id], 'state', false);
        if (function_exists('update_field')) {
            update_field('state', $state_term_id, $city_id);
        }

        // Jobsites: split on newlines only; rejoin orphaned suffix lines
        $lines = preg_split('/\r\n|\r|\n/', $jobsite_raw);
        $lines = array_values(array_filter(array_map('trim', (array)$lines)));

        $repeater = [];
        $buffer = '';
        foreach ($lines as $ln) {
            if (preg_match('/^(inc\.?|corporation|corp\.?|l\.?l\.?c\.?|co\.?|ltd\.?)$/i', $ln)) {
                $buffer .= ' ' . $ln;
            } else {
                if ($buffer !== '') {
                    $repeater[] = ['jobsite_name' => trim($buffer)];
                }
                $buffer = $ln;
            }
        }
        if ($buffer !== '') {
            $repeater[] = ['jobsite_name' => trim($buffer)];
        }

        if (function_exists('update_field')) {
            update_field('jobsites', $repeater, $city_id);
        }
    }

    // Progress + Next link
    echo '<h3>✅ Batch Complete</h3>';
    echo '<p><strong>Processed this batch:</strong> ' . $processed . '</p>';
    echo '<p><strong>Created:</strong> ' . $created . ' &nbsp; <strong>Updated:</strong> ' . $updated . ' &nbsp; <strong>Skipped:</strong> ' . $skipped . '</p>';

    if (!empty($errors)) {
        echo '<h4>Errors & Warnings</h4><ul>';
        foreach ($errors as $e) echo '<li>' . esc_html($e) . '</li>';
        echo '</ul>';
    }

    // Figure out total rows in CSV for navigation (we counted them while streaming)
    // Note: $total_rows is count of data rows AFTER header in the scanned pass; if file is huge and
    // we broke early, we should compute a full total. To keep it simple, do a quick second pass just for total:
    $total_rows_exact = 0;
    if (($fh2 = fopen($csv_file, 'r')) !== false) {
        fgetcsv($fh2); // header
        while (fgetcsv($fh2) !== false) $total_rows_exact++;
        fclose($fh2);
    } else {
        $total_rows_exact = $total_rows; // fallback
    }

    $next_offset = $offset + $batch_size;
    if ($next_offset < $total_rows_exact) {
        $next_url = esc_url( add_query_arg([
            'action'     => 'import',
            'offset'     => $next_offset,
            'batch_size' => $batch_size,
        ]) );
        echo '<p><a href="'.$next_url.'" style="background:#46b450;color:#fff;padding:10px 20px;border-radius:4px;text-decoration:none;">➡️ Import Next Batch (offset '.$next_offset.')</a></p>';
        echo '<p>Total rows in file (excluding header): <strong>'.$total_rows_exact.'</strong></p>';
    } else {
        echo '<p><strong>🎉 All rows imported!</strong></p>';
        echo '<p>Total rows in file (excluding header): <strong>'.$total_rows_exact.'</strong></p>';
    }
}
?>
