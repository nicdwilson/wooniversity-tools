/* Wooni Tools */

jQuery(document).ready( function() {

    jQuery(".wooni-scenario-action").click( function(e) {
        e.preventDefault();
        action_id = jQuery(this).attr("data-wooni-id");
        action = jQuery(this).attr("data-wooni-action");
        nonce = wooniAjax.security;
        console.log('here');

        var request = jQuery.ajax({
            type : "post",
            dataType : "json",
            url : wooniAjax.ajaxurl,
            data : {action: action, nonce: nonce},
        });

        console.log(request);

        request.done( function(response) {
            console.log(response);
            console.log(action_id);
                jQuery("#" + action_id + "-prompt").show('slow');
                jQuery("#" + action_id + "-button").hide('slow');
        });

        request.fail( function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });


    });

    jQuery(".wooni-scenario-solution").click( function(e) {
        e.preventDefault();
        action_id = jQuery(this).attr("data-wooni-id");
        jQuery("#" + action_id + "-solution").show('slow');
    });

    jQuery(".wooni-scenario-hint").click( function(e) {
        e.preventDefault();
        action_id = jQuery(this).attr("data-wooni-id");
        jQuery("#" + action_id + "-hint").show('slow');
    });


});