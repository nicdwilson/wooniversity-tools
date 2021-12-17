/* Wooni Tools */

jQuery(document).ready( function() {

    jQuery(".wooni-scenario-action").click( function(e) {
        e.preventDefault();
        action_id = jQuery(this).attr("id");
        action = jQuery(this).attr("data-wooni-action");
        nonce = wooniAjax.security;

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : wooniAjax.ajaxurl,
            data : {action: action, nonce: nonce},
            success: function(response) {
                if(response.type == "success") {
                    jQuery("#" + action_id + "-prompt").show('slow');
                    jQuery("#" + action_id + "-button").hide('slow');
                }
                else {
                    alert("An error occurred");
                }
            }
        });

    });

});