<?php
/**
 * Template part for displaying the page header
 *
 * @package SWMW_Law
 */

$header_style = get_field('header_style');

if (!$header_style) {
    $header_style = 'basic';
}

// Map of header styles to their template files
$header_templates = [
    'basic' => 'headers/basic',       // Corresponds to template-parts/headers/basic.php
    'with_menu' => 'headers/with-menu',   // Corresponds to template-parts/headers/with-menu.php
    'with_image' => 'headers/with-image' // Corresponds to template-parts/headers/with-image.php
];

// Get the template file for the current header style
$template_file_slug_part = isset($header_templates[$header_style]) ? $header_templates[$header_style] : $header_templates['basic'];

$full_template_slug = 'template-parts/' . $template_file_slug_part;

// Include the appropriate header template
get_template_part($full_template_slug); 
