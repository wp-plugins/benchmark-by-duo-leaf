jQuery(document).ready(function () {

    if (jQuery("#resourceID").val() === "")
        fillAttributesField('CSS');

    jQuery("#resourceType").change(function () {
        fillAttributesField(jQuery('option:selected', this).text());
    });

});

function fillAttributesField(optionType) {
    if (optionType === 'CSS') {
        jQuery('#resourceAttributes').val('type=\'text/css\' media=\'all\'');
    }
    else if (optionType === 'Javascript') {
        jQuery('#resourceAttributes').val('type=\'text/javascript\'');
    }
}
