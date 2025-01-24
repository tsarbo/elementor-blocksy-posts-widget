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

        $this->add_control(
            'bento_grid',
            [
                'label' => esc_html__('Bento Grid Layout', 'beyondweb'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'beyondweb'),
                'label_off' => esc_html__('No', 'beyondweb'),
                'return_value' => 'yes',
                'default' => 'No',
            ]
        );

        $this->add_control(
            'bento_grid_layout',
            [
                'label' => esc_html__('Alignment', 'beyondweb'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'beyondweb'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'beyondweb'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'beyondweb'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
//				'selectors' => [
//					'{{WRAPPER}} .your-class' => 'text-align: {{VALUE}};',
//				],
                'condition' => [
                    'bento_grid' => 'yes',
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

            $class = '';
            $bento_cols = '';
            $bento_col_class = '';
            if (!empty($settings['bento_grid']) && $settings['bento_grid'] == 'yes') {
                $class = 'bento_grid';
                $bento_cols = (int)(($settings['posts_per_page'] - 1) / 2 + 1);
                if ($bento_cols > 3) {
                    $bento_cols = 3;
                }
                if ($bento_cols < 2) {
                    $bento_cols = 2;
                }
                $bento_col_class = 'bento-col-' . $bento_cols;
                $class .= ' ' . $bento_col_class;
            }

            if (!empty($settings['bento_grid_layout'])) {
                $class .= ' ' . $settings['bento_grid_layout'];
            }

            $shortcode = '[blocksy_posts post_ids="' . $ids . '" limit="' . $limit . '" post_type="' . $settings['post_type'] . '" has_pagination="no" filtering="yes" class="' . $class . '" ]';
            error_log('shortcode-->' . print_r($shortcode, true));
            $settings['shortcode'] = $shortcode;


            // Render the shortcode with post IDs.
            echo do_shortcode($shortcode);
            ?>
            <style>

                .bento_grid .entries:not([data-cards=cover]) :is(.entry-button,.entry-meta,
                .bento_grid .ct-media-container):last-child:not(:only-child) {
                    margin-top: 0;
                }

                .bento_grid.left.bento-col-3 .entries {
                    grid-template-columns: repeat(4, 1fr) !important;
                    grid-template-areas:
                        "hero hero  aside1 aside3"
                        "hero hero  aside2 aside4";
                }

                .bento_grid.center.bento-col-3 .entries {
                    grid-template-columns: repeat(4, 1fr) !important;
                    grid-template-areas:
                        "aside1 hero hero aside3"
                        "aside2 hero hero aside4";
                }

                .bento_grid.right.bento-col-3 .entries {
                    grid-template-columns: repeat(4, 1fr) !important;
                    grid-template-areas:
                        "aside1 aside3 hero hero"
                        "aside2 aside4 hero hero";
                }

                .bento_grid.left.bento-col-2 .entries {
                    grid-template-columns: repeat(3, 1fr) !important;
                    grid-template-areas:
                        "hero hero  aside1"
                        "hero hero  aside2";
                }

                .bento_grid.center.bento-col-2 .entries {
                    grid-template-columns: repeat(3, 1fr) !important;
                    grid-template-areas:
                        "aside1 hero hero"
                        "aside2 hero hero";
                }


                .bento_grid.right.bento-col-2 .entries {
                    grid-template-columns: repeat(3, 1fr) !important;
                    grid-template-areas:
                        "aside1 hero hero"
                        "aside2 hero hero";
                }

                @media screen and (min-width: 521px) and (max-width: 1200px) {
                    .bento_grid.left.bento-col-3 .entries,
                    .bento_grid.center.bento-col-3 .entries,
                    .bento_grid.right.bento-col-3 .entries {
                        grid-template-columns: repeat(2, 1fr) !important;
                        grid-template-areas:
                        "hero hero"
                        "aside1 aside2"
                        "aside3 aside4";
                    }


                    .bento_grid.center.bento-col-2 .entries {
                        grid-template-columns: repeat(2, 1fr) !important;
                        grid-template-areas:
                            "hero hero"
                            "aside1 aside2";
                    }

                    .bento_grid.right.bento-col-2 .entries {
                        grid-template-columns: repeat(2, 1fr) !important;
                        grid-template-areas:
                            "hero hero"
                            "aside1 aside2";
                    }


                }

                @media screen and (max-width: 520px) {
                    .bento_grid.left.bento-col-3 .entries,
                    .bento_grid.center.bento-col-3 .entries,
                    .bento_grid.right.bento-col-3 .entries,
                    .bento_grid.center.bento-col-2 .entries,
                    .bento_grid.right.bento-col-2 .entries {
                        grid-template-columns: repeat(1, 1fr) !important;
                        grid-template-areas:
                        "hero"
                        "aside1"
                        "aside2"
                        "aside3"
                        "aside4";
                    }

                    /*.bento_grid.center.bento-col-2 .entries,*/
                    /*.bento_grid.left.bento-col-2 .entries {*/
                    /*    grid-template-columns: repeat(2, 1fr) !important;*/
                    /*    grid-template-areas:*/
                    /*    "hero"*/
                    /*    "aside1"*/
                    /*    "aside2";*/
                    /*}*/
                    /*.bento_grid.center.bento-col-2 .entries {*/
                    /*    grid-template-columns: repeat(2, 1fr) !important;*/
                    /*    grid-template-areas:*/
                    /*        "hero hero"*/
                    /*        "aside1 aside2";*/
                    /*}*/
                    /*.bento_grid.right.bento-col-2 .entries {*/
                    /*    grid-template-columns: repeat(2, 1fr) !important;*/
                    /*    grid-template-areas:*/
                    /*        "hero hero"*/
                    /*        "aside1 aside2";*/
                    /*}*/


                }

                /*@media screen and (max-width: 1600px) {*/
                /*    .bento_grid.center.bento-col-2 .entries {*/
                /*        grid-template-columns: repeat(2, 1fr) !important;*/
                /*        grid-template-areas:*/
                /*            "hero hero"*/
                /*            "aside1 aside2";*/
                /*    }*/
                /*}*/

                .bento_grid .entries .entry-card:nth-child(1) {
                    grid-area: hero;
                }

                .bento_grid .entries .entry-card:nth-child(1) .entry-title {
                    font-size: 2em;
                }

                .bento_grid .entries .entry-card:nth-child(1) .ct-ghost {
                    flex: 0;
                }

                .bento_grid .entries .entry-card:nth-child(2) {
                    grid-area: aside1;
                }

                .bento_grid .entries .entry-card:nth-child(3) {
                    grid-area: aside2;
                }

                .bento_grid .entries .entry-card:nth-child(4) {
                    grid-area: aside3;
                }

                .bento_grid .entries .entry-card:nth-child(5) {
                    grid-area: aside4;
                }

                .bento_grid .entries .entry-card {
                    padding-bottom: 0;
                }
            </style>
            <?php


        } else {
            echo __('No posts found.', 'beyondweb');
        }
    }

    // Render the widget output in the editor.
    protected function content_template()
    {
        ?>
        <#
        var post_ids = '';
        var limit = settings.posts_per_page;
        var post_type = settings.post_type;

        var class_name = '';
        if (settings.bento_grid === 'yes') {
        class_name = 'bento_grid';
        var bento_cols = Math.floor((settings.posts_per_page - 1) / 2 + 1);
        bento_cols = Math.min(Math.max(bento_cols, 2), 3);
        class_name += ' bento-col-' + bento_cols;
        }

        if (settings.bento_grid_layout) {
        class_name += ' ' + settings.bento_grid_layout;
        }

        var shortcode = '[blocksy_posts post_ids="' + post_ids +
        '" limit="' + limit +
        '" post_type="' + post_type +
        '" has_pagination="no" filtering="yes" class="' + class_name + '"]';
        #>
        {{{ shortcode }}}
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