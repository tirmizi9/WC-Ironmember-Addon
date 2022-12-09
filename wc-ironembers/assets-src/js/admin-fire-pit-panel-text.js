function fire_pit_panel_text_toggle_tabs(val)
{
    const $showIfSimple = jQuery('.show_if_simple');
    const $linkedPitOptions = jQuery('.linked_fire_pits_options');
    const $linkedAccessoriesOptions = jQuery('.linked_accessories_options');

    if (val === 'fire-pit-panel-text') {
        $showIfSimple.show();
        $linkedPitOptions.hide();
        $linkedAccessoriesOptions.hide();
    }
}

jQuery(function() {
    jQuery(document.body).on('woocommerce-product-type-change', function(e, val) {
        fire_pit_panel_text_toggle_tabs(val);
    });

    fire_pit_panel_text_toggle_tabs(jQuery('#product-type').val());
});