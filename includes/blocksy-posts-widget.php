<?php

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Elementor_Blocksy_Posts_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'blocksy_posts_widget_with_query';
    }

    public function get_title()
    {
        return __('Blocksy Posts', 'beyondweb');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return ['general'];
    }

    // Register widget controls.
    protected function _register_controls()
    {

//        // Input for the shortcode.
//        $this->start_controls_section(
//            'content_section',
//            [
//                'label' => __('Custom Shortcode', 'beyondweb'),
//                'tab' => Controls_Manager::TAB_CONTENT,
//            ]
//        );
//
//        $this->add_control(
//            'shortcode',
//            [
//                'label' => __('Enter Shortcode', 'beyondweb'),
//                'type' => Controls_Manager::TEXT,
//                'input_type' => 'text',
//                'placeholder' => __('[your_shortcode ids=""]', 'beyondweb'),
//            ]
//        );
//
//        $this->end_controls_section();

        // Post Query Section.
        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Post Query', 'beyondweb'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'beyondweb'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_post_types(),
                'default' => 'post',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of Posts', 'beyondweb'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'beyondweb'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'selectors' => [
                    '{{WRAPPER}} .entries' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        // Include Taxonomy Filter
        $this->add_control(
            'taxonomy_include',
            [
                'label' => __('Taxonomy to Include', 'beyondweb'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_taxonomies(),
                'multiple' => true,
                'default' => [],
            ]
        );

        // Add Taxonomy Terms control
//        $this->add_control(
//            'taxonomy_terms_include',
//            [
//                'label' => __('Taxonomy Terms', 'beyondweb'),
//                'type' => Controls_Manager::SELECT2,
//                'options' => [], // This will be populated dynamically
//                'multiple' => true,
//                'condition' => [
//                    'taxonomy_include!' => '',
//                ],
//            ]
//        );

        $this->add_control(
            'taxonomy_terms_include',
            [
                'label' => __('Include Taxonomy Terms', 'beyondweb'),
                'type' => Controls_Manager::TEXT,
                'description' => 'Enter comma-separated taxonomy terms.',
                'condition' => [
                    'taxonomy_include!' => '',
                ],
            ]
        );

        // Exclude Taxonomy Filter
        $this->add_control(
            'taxonomy_exclude',
            [
                'label' => __('Taxonomy to Exclude', 'beyondweb'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_taxonomies(),
                'multiple' => true,
                'default' => [],
            ]
        );

        // Add Taxonomy Terms control
//        $this->add_control(
//            'taxonomy_terms_exclude',
//            [
//                'label' => __('Taxonomy Terms', 'beyondweb'),
//                'type' => Controls_Manager::SELECT2,
//                'options' => [], // This will be populated dynamically
//                'multiple' => true,
//                'condition' => [
//                    'taxonomy_exclude!' => '',
//                ],
//            ]
//        );

        $this->add_control(
            'taxonomy_terms_exclude',
            [
                'label' => __('Exclude Taxonomy Terms', 'beyondweb'),
                'type' => Controls_Manager::TEXT,
                'description' => 'Enter comma-separated taxonomy terms.',
                'condition' => [
                    'taxonomy_include!' => '',
                ],
            ]
        );

        // Custom field query
        $this->add_control(
            'meta_key',
            [
                'label' => __('Custom Field (Meta Key)', 'beyondweb'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'meta_value',
            [
                'label' => __('Custom Field (Meta Value)', 'beyondweb'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    // Helper function to get post types.
    protected function get_post_types()
    {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];

        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }

        return $options;
    }

    // Helper function to get taxonomies.
    protected function get_taxonomies()
    {
        $taxonomies = get_taxonomies(['public' => true], 'objects');
        $options = [];

        foreach ($taxonomies as $taxonomy) {
            $options[$taxonomy->name] = $taxonomy->label;
        }

        return $options;
    }

    // Render the widget output.
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        error_log('settings-->' . print_r($settings, true));

        // Query Arguments
        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
        ];

        // Taxonomy Include Filter
        if (!empty($settings['taxonomy_include']) && !empty($settings['taxonomy_terms_include'])) {
            $args['tax_query'][] = [
                'taxonomy' => $settings['taxonomy_include'],
                'field' => 'term_id', // or 'term_id' depending on how you want to filter
                'terms' => $settings['taxonomy_terms_include'],
                'operator' => 'IN',
            ];
        }

//        if (!empty($settings['taxonomy_terms_include2'])) {
//            $args['tax_query'][] = [
//                'taxonomy' => $settings['taxonomy_include'],
//                'field' => 'term_id', // or 'term_id' depending on how you want to filter
//                'terms' => explode(',', $settings['taxonomy_terms_include2']),
//                'operator' => 'IN',
//            ];
//        }

        // Taxonomy Exclude Filter
        if (!empty($settings['taxonomy_exclude']) && !empty($settings['taxonomy_terms_exclude'])) {
            $args['tax_query'][] = [
                'taxonomy' => $settings['taxonomy_exclude'],
                'field' => 'term_id', // or 'term_id' depending on how you want to filter
                'terms' => $settings['taxonomy_terms_exclude'],
                'operator' => 'IN',
            ];
        }

        // Custom Field Query
        if (!empty($settings['meta_key']) && !empty($settings['meta_value'])) {
            $args['meta_query'] = [
                [
                    'key' => $settings['meta_key'],
                    'value' => $settings['meta_value'],
                    'compare' => 'LIKE', // Adjust this depending on your use case
                ],
            ];
        }

        error_log('args-->' . print_r($args, true));

        // Query the posts.
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            // Collect post IDs to pass to the shortcode.
            $post_ids = [];

            while ($query->have_posts()) {
                $query->the_post();
                $post_ids[] = get_the_ID();
            }

            // Reset post data after the loop.
            wp_reset_postdata();

            // Prepare the shortcode string with post IDs.
            $ids = '';
            if (!empty($post_ids)) {
                $ids = implode(',', $post_ids);
                // Append post IDs to the shortcode, assuming it has an 'ids' attribute.
            }
            $limit = '';
            if (!empty($settings['posts_per_page'])) {
//                $ids = implode( ',', $post_ids );
                // Append post IDs to the shortcode, assuming it has an 'ids' attribute.
                $limit = $settings['posts_per_page'];
            }
            $shortcode = '[blocksy_posts post_ids="' . $ids . '" limit="' . $limit . '" post_type="' . $settings['post_type'] . '" has_pagination="no" filtering="yes"]';
            error_log('shortcode-->' . print_r($shortcode, true));
            $settings['shortcode'] = $shortcode;

            // Render the shortcode with post IDs.
            echo do_shortcode($shortcode);
        } else {
            echo __('No posts found.', 'beyondweb');
        }
    }

    // Render the widget output in the editor.
    protected function _content_template()
    {
        ?>
        <# if ( settings.shortcode ) { #>
        {{{ settings.shortcode }}}
        <# } else { #>
        <span><?php _e('Please enter a valid shortcode.', 'beyondweb'); ?></span>
        <# } #>
        <?php
    }

    protected function _content_template_back()
    {
        ?>
        <#
        var postType = settings.post_type || 'post';
        var postsPerPage = settings.posts_per_page || 5;
        var shortcode = '[blocksy_posts post_type="' + postType + '" limit="' + postsPerPage + '" has_pagination="no" filtering="yes"]';
        #>
        {{{ shortcode }}}
        <?php
    }
}


add_action('wp_ajax_get_taxonomy_terms', 'get_taxonomy_terms_callback');
add_action('wp_ajax_nopriv_get_taxonomy_terms', 'get_taxonomy_terms_callback');


function get_taxonomy_terms_callback()
{
    check_ajax_referer('elementor_widget_controls_nonce', 'nonce');

    $taxonomy = $_POST['taxonomy'];
    $search = $_POST['search'];

    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'search' => $search,
    ]);

    $results = array_map(function ($term) {
        return ['id' => $term->term_id, 'text' => $term->name];
    }, $terms);

    wp_send_json($results);
}




//// Handle AJAX request to get terms by taxonomy
//add_action('wp_ajax_get_taxonomy_terms', 'get_taxonomy_terms');
//add_action('wp_ajax_nopriv_get_taxonomy_terms', 'get_taxonomy_terms'); // For non-logged in users
//
//function get_taxonomy_terms()
//{
//    // Verify that taxonomy is set
//    if (isset($_POST['taxonomy']) && taxonomy_exists($_POST['taxonomy'])) {
//        $taxonomy = sanitize_text_field($_POST['taxonomy']);
//
//        // Get terms for the selected taxonomy
//        $terms = get_terms([
//            'taxonomy' => $taxonomy,
//            'hide_empty' => false,
//        ]);
//
//        if (!empty($terms) && !is_wp_error($terms)) {
//            $term_data = [];
//
//            // Format terms for response
//            foreach ($terms as $term) {
//                $term_data[$term->term_id] = $term->name;
//            }
//
//            wp_send_json_success($term_data);
//        } else {
//            wp_send_json_error('No terms found.');
//        }
//    }
//
//    wp_die();
//}