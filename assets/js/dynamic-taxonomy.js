jQuery(document).ready(function ($) {

    // Listen for changes on the taxonomy select
    $(document).on('change', '[data-setting="taxonomy_include"]', function () {
        var taxonomy = $(this).val();

        // If taxonomy is selected, fetch terms via AJAX
        if (taxonomy) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_taxonomy_terms', // The action to call in PHP
                    taxonomy: taxonomy
                },
                success: function (response) {
                    if (response.success) {
                        // Populate the terms select with new options
                        var $termsSelect = $('[data-setting="taxonomy_terms_include"]');
                        $termsSelect.empty(); // Clear existing options

                        $.each(response.data, function (id, name) {
                            $termsSelect.append(new Option(name, id));
                        });

                        $termsSelect.trigger('change'); // Trigger change event to refresh UI
                    }
                }
            });
        }
    });

    $(document).on('change', '[data-setting="taxonomy_exclude"]', function () {
        var taxonomy = $(this).val();

        // If taxonomy is selected, fetch terms via AJAX
        if (taxonomy) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_taxonomy_terms', // The action to call in PHP
                    taxonomy: taxonomy
                },
                success: function (response) {
                    if (response.success) {
                        // Populate the terms select with new options
                        var $termsSelect = $('[data-setting="taxonomy_terms_exclude"]');
                        $termsSelect.empty(); // Clear existing options

                        $.each(response.data, function (id, name) {
                            $termsSelect.append(new Option(name, id));
                        });

                        $termsSelect.trigger('change'); // Trigger change event to refresh UI
                    }
                }
            });
        }
    });

});

// This ensures that the script is run in the editor context as well
jQuery(window).on('elementor/frontend/init', function () {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope, $) {
        // Listen for changes on the taxonomy select
        $(document).on('change', '[data-setting="taxonomy_include"]', function () {
            var taxonomy = $(this).val();
            console.log('Taxonomy:', taxonomy);
            // If taxonomy is selected, fetch terms via AJAX
            if (taxonomy) {
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_taxonomy_terms', // The action to call in PHP
                        taxonomy: taxonomy
                    },
                    success: function (response) {
                        if (response.success) {
                            // Populate the terms select with new options
                            var $termsSelect = $('[data-setting="taxonomy_terms_include"]');
                            $termsSelect.empty(); // Clear existing options

                            $.each(response.data, function (id, name) {
                                $termsSelect.append(new Option(name, id));
                            });

                            $termsSelect.trigger('change'); // Trigger change event to refresh UI
                        }
                    }
                });
            }
        });

        $(document).on('change', '[data-setting="taxonomy_exclude"]', function () {
            var taxonomy = $(this).val();

            // If taxonomy is selected, fetch terms via AJAX
            if (taxonomy) {
                $.ajax({
                    url: ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_taxonomy_terms', // The action to call in PHP
                        taxonomy: taxonomy
                    },
                    success: function (response) {
                        if (response.success) {
                            // Populate the terms select with new options
                            var $termsSelect = $('[data-setting="taxonomy_terms_exclude"]');
                            $termsSelect.empty(); // Clear existing options

                            $.each(response.data, function (id, name) {
                                $termsSelect.append(new Option(name, id));
                            });

                            $termsSelect.trigger('change'); // Trigger change event to refresh UI
                        }
                    }
                });
            }
        });
    });
});