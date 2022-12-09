jQuery( function( $ ) {

    function getEnhancedSelectFormatString() {
        return {
            'language': {
                errorLoading: function() {
                    // Workaround for https://github.com/select2/select2/issues/4355 instead of i18n_ajax_error.
                    return tf_wc_product_fire_pit_params.i18n_searching;
                },
                inputTooLong: function( args ) {
                    var overChars = args.input.length - args.maximum;

                    if ( 1 === overChars ) {
                        return tf_wc_product_fire_pit_params.i18n_input_too_long_1;
                    }

                    return tf_wc_product_fire_pit_params.i18n_input_too_long_n.replace( '%qty%', overChars );
                },
                inputTooShort: function( args ) {
                    var remainingChars = args.minimum - args.input.length;

                    if ( 1 === remainingChars ) {
                        return tf_wc_product_fire_pit_params.i18n_input_too_short_1;
                    }

                    return tf_wc_product_fire_pit_params.i18n_input_too_short_n.replace( '%qty%', remainingChars );
                },
                loadingMore: function() {
                    return tf_wc_product_fire_pit_params.i18n_load_more;
                },
                maximumSelected: function( args ) {
                    if ( args.maximum === 1 ) {
                        return tf_wc_product_fire_pit_params.i18n_selection_too_long_1;
                    }

                    return tf_wc_product_fire_pit_params.i18n_selection_too_long_n.replace( '%qty%', args.maximum );
                },
                noResults: function() {
                    return tf_wc_product_fire_pit_params.i18n_no_matches;
                },
                searching: function() {
                    return tf_wc_product_fire_pit_params.i18n_searching;
                }
            }
        };
    }

    try {
        $(function() {

            $( document.body ).on( 'woocommerce-product-type-change', function(e, val) {
                jQuery('.linked_posts_options').hide();
                if (val === 'fire-pit') {
                    jQuery('#general_product_data .pricing').addClass('show_if_fire-pit').show();
                    jQuery('.inventory_options').addClass('show_if_fire-pit').show();
                    jQuery('.show_if_simple').show();
                    jQuery('.linked_posts_options').show();
                }
            });

            jQuery( document ).ready(function() {
                jQuery('.linked_posts_options').hide();
                if (jQuery( '#product-type ').val() === 'fire-pit') {
                    jQuery('#general_product_data .pricing').addClass('show_if_fire-pit').show();
                    jQuery('.inventory_options').addClass('show_if_fire-pit').show();
                    jQuery('.show_if_simple').show();
                    jQuery('.linked_posts_options').show();
                }
            });

            // Ajax product search box
            $( ':input.wc-post-search' ).filter( ':not(.enhanced)' ).each( function() {
                var select2_args = {
                    allowClear:  $( this ).data( 'allow_clear' ) ? true : false,
                    placeholder: $( this ).data( 'placeholder' ),
                    minimumInputLength: $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : '3',
                    escapeMarkup: function( m ) {
                        return m;
                    },
                    ajax: {
                        url:         tf_wc_product_fire_pit_params.ajax_url,
                        dataType:    'json',
                        delay:       250,
                        data:        function( params ) {
                            return {
                                term         : params.term,
                                action       : $( this ).data( 'action' ) || 'woocommerce_json_search_posts',
                                security     : tf_wc_product_fire_pit_params.search_posts_nonce,
                                exclude      : $( this ).data( 'exclude' ),
                                exclude_type : $( this ).data( 'exclude_type' ),
                                include      : $( this ).data( 'include' ),
                                limit        : $( this ).data( 'limit' ),
                                display_stock: $( this ).data( 'display_stock' )
                            };
                        },
                        processResults: function( data ) {
                            var terms = [];
                            if ( data ) {
                                $.each( data, function( id, text ) {
                                    terms.push( { id: id, text: text } );
                                });
                            }
                            return {
                                results: terms
                            };
                        },
                        cache: true
                    }
                };

                select2_args = $.extend( select2_args, getEnhancedSelectFormatString() );

                $( this ).selectWoo( select2_args ).addClass( 'enhanced' );

                if ( $( this ).data( 'sortable' ) ) {
                    var $select = $(this);
                    var $list   = $( this ).next( '.select2-container' ).find( 'ul.select2-selection__rendered' );

                    $list.sortable({
                        placeholder : 'ui-state-highlight select2-selection__choice',
                        forcePlaceholderSize: true,
                        items       : 'li:not(.select2-search__field)',
                        tolerance   : 'pointer',
                        stop: function() {
                            $( $list.find( '.select2-selection__choice' ).get().reverse() ).each( function() {
                                var id     = $( this ).data( 'data' ).id;
                                var option = $select.find( 'option[value="' + id + '"]' )[0];
                                $select.prepend( option );
                            } );
                        }
                    });
                    // Keep multiselects ordered alphabetically if they are not sortable.
                } else if ( $( this ).prop( 'multiple' ) ) {
                    $( this ).on( 'change', function(){
                        var $children = $( this ).children();
                        $children.sort(function(a, b){
                            var atext = a.text.toLowerCase();
                            var btext = b.text.toLowerCase();

                            if ( atext > btext ) {
                                return 1;
                            }
                            if ( atext < btext ) {
                                return -1;
                            }
                            return 0;
                        });
                        $( this ).html( $children );
                    });
                }
            });
        })
    } catch( err ) {
        // If select2 failed (conflict?) log the error but don't stop other scripts breaking.
        window.console.log( err );
    }
});
