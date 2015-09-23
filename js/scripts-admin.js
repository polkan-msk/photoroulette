jQuery(document).ready(function($) {
    $(document).on("click", ".addDefImageBtn", function() {

        jQuery.data(document.body, 'inputElement', $(this).prev());

        window.send_to_editor = function(html) {
            regexp = /src="([^"]+)"/;
            img = regexp.exec(html); 
            src = img[1];
            var inputElement = jQuery.data(document.body, 'inputElement');

            if(inputElement != undefined && src != '')
                inputElement.val(src);

            tb_remove();
        };

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });



});
