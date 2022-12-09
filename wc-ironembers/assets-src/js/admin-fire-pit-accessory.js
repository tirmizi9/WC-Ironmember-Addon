function fire_pit_accessory_toggle_tabs(val)
{
    const $showIfSimple = jQuery('.show_if_simple');
    const $linkedPitOptions = jQuery('.linked_fire_pits_options')
    $linkedPitOptions.hide();
    if (val === 'fire-pit-accessory') {
        $showIfSimple.show();
        $linkedPitOptions.show();
    }
}

jQuery(function() {
    jQuery(document.body).on('woocommerce-product-type-change', function(e, val) {
        fire_pit_accessory_toggle_tabs(val);
    });

    fire_pit_accessory_toggle_tabs(jQuery('#product-type').val());
});