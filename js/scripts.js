// refresh handler 
function pwpprPostRefresh ( idnumber ){

    var myvars = window['pwppr_' + idnumber];

    // user choise of num posts
    numPosts = jQuery('#my-'+myvars.id+' .userNumPosts').val().replace(/[^\d]/gi,'');
    if (Number(numPosts)>Number(myvars.maxitems)) { numPosts=myvars.maxitems;}
    numPosts = numPosts || 0;
    if(numPosts) jQuery('#my-'+myvars.id+' .userNumPosts').val(numPosts);
    else         jQuery('#my-'+myvars.id+' .userNumPosts').val(myvars.items2show);

    jQuery('#my-'+myvars.id+' .pwpprRefreshCont').css("backgroundPosition", "right -128px");
    jQuery.ajax({
        type: "POST",
        url: "/index.php",
        data: {
            flag: "pwpprPostRefreshFlag",
            numPosts: numPosts,
            number: idnumber,
            id: '"'+myvars.id+'"'
        },
        success: function(data){
            jQuery('#my-'+myvars.id+' .pwppr-posts-container').html(data);
            jQuery('#my-'+myvars.id+' .pwpprRefreshCont').css("backgroundPosition", "");
        },
        error: function(){
            jQuery('#my-'+myvars.id+' .pwppr-posts-container').html('<span style="color:red;"><b>error</b></span>');
        }
    });

}

