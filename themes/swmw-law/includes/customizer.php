<?php
/**
 * SWMW Law Theme Customizer
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            [
                'selector'        => '.site-title a',
                'render_callback' => __NAMESPACE__ . '\customize_partial_blogname',
            ]
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            [
                'selector'        => '.site-description',
                'render_callback' => __NAMESPACE__ . '\customize_partial_blogdescription',
            ]
        );
    }

    // Add Theme Options Panel
    $wp_customize->add_panel(
        'swmw_law_options',
        [
            'title'       => __( 'SWMW Law Options', 'swmw-law' ),
            'description' => __( 'Theme options for SWMW Law', 'swmw-law' ),
            'priority'    => 130,
        ]
    );

    // Add Contact Information Section
    $wp_customize->add_section(
        'swmw_law_contact_info',
        [
            'title'    => __( 'Contact Information', 'swmw-law' ),
            'panel'    => 'swmw_law_options',
            'priority' => 10,
        ]
    );

    // Add Contact Information Settings
    $wp_customize->add_setting(
        'swmw_law_phone',
        [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    $wp_customize->add_control(
        'swmw_law_phone',
        [
            'label'    => __( 'Phone Number', 'swmw-law' ),
            'section'  => 'swmw_law_contact_info',
            'type'     => 'text',
        ]
    );

    $wp_customize->add_setting(
        'swmw_law_email',
        [
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        ]
    );

    $wp_customize->add_control(
        'swmw_law_email',
        [
            'label'    => __( 'Email Address', 'swmw-law' ),
            'section'  => 'swmw_law_contact_info',
            'type'     => 'email',
        ]
    );

    // Footer Content Section
    $wp_customize->add_section( 'swmw_law_footer_content_section', array(
        'title'       => __( 'Footer Content', 'swmw-law' ),
        'priority'    => 160, // Adjust priority to position it appropriately
        'description' => __( 'Manage the content of the site footer.', 'swmw-law' ),
    ) );

    // Footer Copyright Text Setting & Control
    $wp_customize->add_setting( 'swmw_law_footer_copyright_text', array(
        'default'           => sprintf( 
            __( '&copy; [current_year] All Rights Reserved. %1$s | %2$s', 'swmw-law' ),
            '<a href="' . esc_url( home_url( '/site-map/' ) ) . '">' . __( 'Site Map', 'swmw-law' ) . '</a>',
            '<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . __( 'Privacy Policy', 'swmw-law' ) . '</a>'
        ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_footer_copyright_text_control', array(
        'label'       => __( 'Footer Copyright Text', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_footer_copyright_text',
        'type'        => 'textarea',
        'description' => __( 'Enter your copyright text. Use [current_year] for the dynamic year. HTML is allowed for links.', 'swmw-law' ),
    ) );

    // Footer Disclaimer Text Setting & Control
    $wp_customize->add_setting( 'swmw_law_footer_disclaimer_text', array(
        'default'           => __( 'The choice of a lawyer is an important decision and should not be based solely upon advertisements. Results obtained depend upon the facts of each case. Past results afford no guarantee of future results or similar outcomes. Every case is different and must be judged on its own merits. The information on this website is for general information purposes only. Nothing on this site should be taken as legal advice for any individual case or situation. This information is not intended to create, and receipt or viewing does not constitute, an attorney-client relationship.', 'swmw-law' ),
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_footer_disclaimer_text_control', array(
        'label'       => __( 'Footer Disclaimer Text', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_footer_disclaimer_text',
        'type'        => 'textarea',
        'description' => __( 'Enter your footer disclaimer text. HTML is allowed.', 'swmw-law' ),
    ) );

    // Social Media URLs
    $wp_customize->add_setting( 'swmw_law_social_facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_social_facebook_url_control', array(
        'label'       => __( 'Facebook URL', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_social_facebook_url',
        'type'        => 'url',
    ) );

    $wp_customize->add_setting( 'swmw_law_social_instagram_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_social_instagram_url_control', array(
        'label'       => __( 'Instagram URL', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_social_instagram_url',
        'type'        => 'url',
    ) );

    $wp_customize->add_setting( 'swmw_law_social_bbb_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_social_bbb_url_control', array(
        'label'       => __( 'BBB URL', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_social_bbb_url',
        'type'        => 'url',
    ) );

    $wp_customize->add_setting( 'swmw_law_social_linkedin_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );

    $wp_customize->add_control( 'swmw_law_social_linkedin_url_control', array(
        'label'       => __( 'LinkedIn URL', 'swmw-law' ),
        'section'     => 'swmw_law_footer_content_section',
        'settings'    => 'swmw_law_social_linkedin_url',
        'type'        => 'url',
    ) );
}
add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );

/**
 * Render the site title for the selective refresh partial.
 */
function customize_partial_blogname() {
    bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 */
function customize_partial_blogdescription() {
    bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js() {
    wp_enqueue_script(
        'swmw-law-customizer',
        SWMW_LAW_URI . '/dist/js/customizer.js',
        [ 'customize-preview' ],
        SWMW_LAW_VERSION,
        true
    );
}
add_action( 'customize_preview_init', __NAMESPACE__ . '\customize_preview_js' ); 
