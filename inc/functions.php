<?php

/*
 * Get posts
 */
function get_pwppr_posts( $instance ){
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

    $echo = '<div class="pwppr-list">';
    if ( $theQuery->have_posts() ) {
        while ( $theQuery->have_posts() ) {
            $theQuery->the_post();
            
            global $post;
                 
            $post_id = $post->ID;
            $post_title = $post->post_title;
            $post_link = get_permalink();

            // get image as defined in setings
            $img = '';
            if ( $instance['imgSource'] == 'contentFirst' || $instance['imgSource'] == 'contentRand' ){
                preg_match_all("|<img[^']*?src=\"([^']*?)\"[^']*?>|", $post->post_content, $matches);
                if ( is_array($matches[1]) && count($matches[1]) ){
                    if ($instance['imgSource'] == 'contentFirst'){
                        $img = $matches[1][0];
                    }
                    else{
                        $img = $matches[1][array_rand($matches[1])];
                    }
                }
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


            $echo .= '<div class="pwppr-item" 
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
    $echo .= '</div>';

    return $echo;
}

