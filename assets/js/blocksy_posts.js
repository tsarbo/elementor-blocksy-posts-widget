jQuery(window).on('elementor/frontend/init', function () {
    // elementor.hooks.addAction('panel/open_editor/widget/blocksy_posts_widget_with_query', function (panel, model, view) {
    //     // var $taxonomyIncludeControl = panel.$el.find('[data-setting="taxonomy_include"]');
    //     // var $termsIncludeControl = panel.$el.find('[data-setting="taxonomy_include_terms"]');
    //     // console.log('Taxonomy Include Control:', $taxonomyIncludeControl);
    //     // $taxonomyIncludeControl.on('change', function () {
    //     //     console.log('Taxonomy Include Control Changed:', $taxonomyIncludeControl);
    //     //     var taxonomy = $(this).val();
    //     //     if (taxonomy) {
    //     //         // AJAX call to get terms for the selected taxonomy
    //     //         $.ajax({
    //     //             url: ajaxurl,
    //     //             data: {
    //     //                 action: 'get_taxonomy_terms',
    //     //                 taxonomy: taxonomy,
    //     //                 nonce: elementor_widget_controls.nonce
    //     //             },
    //     //             success: function (response) {
    //     //                 $termsIncludeControl.html(response);
    //     //             }
    //     //         });
    //     //     } else {
    //     //         $termsIncludeControl.html('');
    //     //     }
    //     // });
    //
    //     // $taxonomyIncludeControl.on('select2:select', function (e) {
    //     //     console.log('Taxonomy Include Control Changed:', $taxonomyIncludeControl);
    //     //     var taxonomy = e.params.data.id;
    //     //     if (taxonomy) {
    //     //         jQuery.ajax({
    //     //             url: ajaxurl,
    //     //             type: 'POST',
    //     //             data: {
    //     //                 action: 'get_taxonomy_terms',
    //     //                 taxonomy: taxonomy,
    //     //                 nonce: elementor_widget_controls.nonce
    //     //             },
    //     //             success: function (response) {
    //     //                 $termsControl.html(response).trigger('change');
    //     //             }
    //     //         });
    //     //     } else {
    //     //         $termsControl.html('').trigger('change');
    //     //     }
    //     // });
    //
    //     var taxonomyIncludeControl = view.model.get('settings').get('taxonomy_include');
    //     var termsIncludeControl = view.model.get('settings').get('taxonomy_include_terms');
    //
    //     taxonomyIncludeControl.on('change:external', function (model) {
    //         var taxonomy = model.get('value');
    //         if (taxonomy) {
    //             jQuery.ajax({
    //                 url: elementor_widget_controls.ajaxurl,
    //                 type: 'POST',
    //                 data: {
    //                     action: 'get_taxonomy_terms',
    //                     taxonomy: taxonomy,
    //                     nonce: elementor_widget_controls.nonce
    //                 },
    //                 success: function (response) {
    //                     termsIncludeControl.set('options', JSON.parse(response));
    //                 }
    //             });
    //         } else {
    //             termsIncludeControl.set('options', {});
    //         }
    //     });
    //
    //     // $termsIncludeControl.select2({
    //     //     ajax: {
    //     //         url: elementor_widget_controls.ajaxurl,
    //     //         dataType: 'json',
    //     //         delay: 250,
    //     //         data: function (params) {
    //     //             return {
    //     //                 action: 'get_taxonomy_terms',
    //     //                 taxonomy: $taxonomyIncludeControl.val(),
    //     //                 search: params.term,
    //     //                 nonce: elementor_widget_controls.nonce
    //     //             };
    //     //         },
    //     //         processResults: function (data) {
    //     //             return {
    //     //                 results: data
    //     //             };
    //     //         },
    //     //         cache: true
    //     //     },
    //     //     minimumInputLength: 2
    //     // });
    //
    //
    //     var $taxonomyExludeControl = panel.$el.find('[data-setting="taxonomy_exclude"]');
    //     var $termsExludeControl = panel.$el.find('[data-setting="taxonomy_exclude_terms"]');
    //
    //     $taxonomyExludeControl.on('change', function () {
    //         var taxonomy = $(this).val();
    //         if (taxonomy) {
    //             // AJAX call to get terms for the selected taxonomy
    //             $.ajax({
    //                 url: ajaxurl,
    //                 data: {
    //                     action: 'get_taxonomy_terms',
    //                     taxonomy: taxonomy,
    //                     nonce: elementor_widget_controls.nonce
    //                 },
    //                 success: function (response) {
    //                     $termsExludeControl.html(response);
    //                 }
    //             });
    //         } else {
    //             $termsExludeControl.html('');
    //         }
    //     });
    // });

    elementor.channels.editor.on('elementor:editor:init', function () {
        elementor.hooks.addAction('panel/open_editor/widget/shortcode_widget_with_query', function (panel, model, view) {
            var taxonomyIncludeControl = view.controls.taxonomy_include;
            var termsIncludeControl = view.controls.taxonomy_include_terms;

            view.listenTo(taxonomyIncludeControl, 'change', function (changedModel) {
                var taxonomy = changedModel.get('value');
                if (taxonomy) {
                    jQuery.ajax({
                        url: elementor_widget_controls.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_taxonomy_terms',
                            taxonomy: taxonomy,
                            nonce: elementor_widget_controls.nonce
                        },
                        success: function (response) {
                            termsIncludeControl.set('options', JSON.parse(response));
                        }
                    });
                } else {
                    termsIncludeControl.set('options', {});
                }
            });
        });
    });

});

