/*
jQuery(document).ready(function($) {

    function pwpprPostRefresh ( idnumber ){

        id = pwppr_

        // user choise of num posts
        numPosts = jQuery('#<?php echo $this->id; ?> .userNumPosts').val().replace(/[^\d]/gi,'');
        if (numPosts><?php echo $this->maxitems; ?>) numPosts=<?php echo $this->maxitems; ?>;
        numPosts = numPosts || 0;
        if(numPosts) jQuery('#<?php echo $this->id; ?> .userNumPosts').val(numPosts);
        else         jQuery('#<?php echo $this->id; ?> .userNumPosts').val('<?php echo $instance['items2show']; ?>');

        jQuery("#<?php echo $this->id; ?> .pwpprRefreshCont").css("backgroundPosition", "right -128px");
        jQuery.ajax({
            type: "POST",
            url: "index.php",
            data: {
                flag: "pwpprPostRefreshFlag",
                numPosts: numPosts,
                number: idnumber,
                id: "<?php echo $this->id; ?>"
            },
            success: function(data){
                jQuery('#<?php echo $this->id; ?> .pwppr-posts-container').html(data);
                jQuery("#<?php echo $this->id; ?> .pwpprRefreshCont").css("backgroundPosition", "");
            },
            error: function(){
                jQuery('#<?php echo $this->id; ?> .pwppr-posts-container').html('<span style="color:red;"><b>error</b></span>');
            }
        });
    }

});
*/
