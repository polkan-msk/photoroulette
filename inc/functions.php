<?php

/*
 * Get posts
 */
function pwppr_posts( $instance ){
    //echo '<pre>'.print_r($instance,1).'</pre>';
    // no needed wp_parse_args with defaults here. $instance is ok
    $args = array(
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'posts_per_page'=> $instance['items2show'],
            'orderby'       => 'rand',
            );
    if ( ! empty( $instance['categories'] ) )
        $args['category__in'] = $instance['categories'];

    $theQuery = new WP_Query( $args );

    echo '<div class="pwppr-list">';
    if ( $theQuery->have_posts() ) {
        while ( $theQuery->have_posts() ) {
            $theQuery->the_post();
            
            $post_id = get_the_ID();
            $post_link = get_permalink();
            $post_title = get_the_title();

            // get image as defined in setings
            $img = '';
            if ( $instance['imgSource'] == 'contentFirst' ){
                $match_count = preg_match("|<img[^']*?src=\"([^']*?)\"[^']*?>|", get_the_content(), $matches);
                $img = $matches[1];
            }
            else {
                // post attachments		 
                $p = array(
                        'numberposts' => -1,
                        'post_type' => 'attachment',
                        'post_mime_type' => 'image',
                        'order' => 'ASC',
                        'orderby' => 'menu_order ID',
                        'post_status' => 'inherit',
                        'post_parent' => $post_id
                        );
                $attachments = get_posts($p);

                if ($attachments) {
                    $attachNum = 0; // $instance['imgSource'] == 'attachedFirst'
                    if ( $instance['imgSource'] == 'attachedRand' )
                        $attachNum = mt_rand(0, (count($attachments)-1));

                    $attachmentId = $attachments[$attachNum]->ID;
                    $imgsrc = wp_get_attachment_image_src($attachmentId, 'thumbnail');
                    $thumb = $imgsrc[0];
                    $full = str_replace('-150x150','',$thumb);

                    $img = $thumb;
                }
            }
            if ( empty($img) && !empty($instance['defImage']) )
                $img = $instance['defImage'];

            if ( empty($img) )
                continue;

            // resize img to fulfill pwppr-item block
            $imgsize = getimagesize($img);
            $realImgW = $imgsize[0];
            $realImgH = $imgsize[1];
            $realRatio = $realImgW / $realImgH;
            $newImgW = $instance['thumbW'];
            $newImgH = ceil ( $newImgW / $realRatio );
            if ( $newImgH < $instance['thumbH']){
                $newImgH = $instance['thumbH'];
                $newImgW = ceil ( $newImgH * $realRatio );
            }


            $title_block = '';
            if ( $instance['isShowTitles'] == 'on' ) 
                $title_block = '<div class="pwppr-item-title">'
                             . $post_title 
                             //. ' (' . implode( ', ', wp_get_post_categories( $post_id, array('fields'=>'names') ) ). ')' 
                             . '</div>';


            echo '<div class="pwppr-item" 
                       style="width:'.$instance['thumbW'].'px;
                              height:'.$instance['thumbH'].'px;
                              margin:'.$instance['itemMargin'].'px;">' 
                . '<a href="'.$post_link.'">'
                . '<img src="'.$img.'" style="width:'.$newImgW.'px;height:'.$newImgH.'px;">'
                . $title_block
                . '</a>'
                . '</div>';

        } //while
    } //if
    echo '</div>';
}



/*
 * Ajax handler
 */
function pwppr_ajax_handler() {

    if ((isset($_POST['flag'])) && ($_POST['flag'] == "pwpprPostRefreshFlag")){

        $wdgt_num = $_POST['number'];

        $pwppr_ajax = new PWP_Photoroulette_Widget;
        $options = get_option($pwppr_ajax->option_name);
        $options = $options[ $wdgt_num ];

        if ( ! empty($_POST['numPosts']) )
            $options['items2show'] = $_POST['numPosts'];

        pwppr_posts ( $options );

        die();
    }
}
// Add the handler to init()
add_action('init', 'pwppr_ajax_handler');


