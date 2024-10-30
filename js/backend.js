jQuery( document ).ready(function($) {
    jQuery.each($('.ctac_GetListsFromAC'), function( index, value ) {
        var $ph = $(value)
        jQuery.ajax({
                url: ajaxurl,
                data: {action:'ctac_GetListsFromAC', selected:$ph.attr('data-selected-list-id'), name:$ph.attr('data-select-name')},
                type: 'POST',
                dataType: 'json',
                success: function(data){
                    //console.log(data)
                    $ph.html(data.select)
                }
        });
        
    });
});