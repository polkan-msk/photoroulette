// refresh actions handler 
jQuery(document).ready(function($) {
    $(document).on('click', '.ajaxSpinBtn, .ajaxSquareBtn', function(event){
        event.preventDefault();
        pwpprPostRefresh( $(this).data('idnumber') );
    });
    $(document).on('keyup', '.userNumPosts', function(event){
        event.preventDefault();
        pwpprPostRefresh( $(this).data('idnumber') );
    });
    $(document).on('click', '.userNumPosts', function(){
        $(this).select();
    });
});

// refresh ajax function
function pwpprPostRefresh ( idnumber ){

    var currBlockVars = window['pwppr_' + idnumber];

    // user choise of num posts
    numPosts = jQuery('#my-'+currBlockVars.id+' .userNumPosts').val().replace(/[^\d]/gi,'');
    if (Number(numPosts)>Number(currBlockVars.maxitems)) { numPosts=currBlockVars.maxitems;}
    numPosts = numPosts || 0;
    if(numPosts) jQuery('#my-'+currBlockVars.id+' .userNumPosts').val(numPosts);
    else         jQuery('#my-'+currBlockVars.id+' .userNumPosts').val(currBlockVars.items2show);

    jQuery('#my-'+currBlockVars.id+' .pwpprRefreshCont').css("backgroundPosition", "right -128px");

    var data = { 
        nonce: pwppr.nonce,
        action: 'pwppr_actions',
        flag: 'pwpprPostRefreshFlag',
        numPosts: numPosts,
        number: idnumber,
        id: '"'+currBlockVars.id+'"'
    };
    jQuery.post(pwppr.ajaxurl, data, function(res) {

        if (res.success) {
            jQuery('#my-'+currBlockVars.id+' .pwppr-posts-container').html(res.data);
            jQuery('#my-'+currBlockVars.id+' .pwpprRefreshCont').css("backgroundPosition", "");
        } else {
            if(res.data) alert(res.data);
            else alert(pwppr.errorMessage);
        }
        jQuery('#my-'+currBlockVars.id+' .pwpprRefreshCont').css("backgroundPosition", "");
    });
}

