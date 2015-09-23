<?php
// don't call file directly
if ( !defined( 'ABSPATH' ) )
    exit;

class PWP_Photoroulette_Widget extends WP_Widget {

    private $maxitems;
    private $colorSchemes;
    private $defaults;
    /*
     * Create options
     */ 
    public function __construct() {
        parent::__construct(
            'pwppr',
            __( 'PhotoRoulette', 'pwppr' ),
            array( 'description' => __( 'Increase the pageviews of your site!', 'pwppr' ), )
        );
        // maximum items to show
        $this->maxitems = 20;
        // color schemes
        $this->colorSchemes = array('green'=>'#00d500','blue'=>'#129dcb', 'red'=>'#d50000', 'yellow'=>'#d5d500', 'gray'=>'#a5a5a5');
        // defaults settings
        $this->defaults = array(
            'title' => __( 'PhotoRoulette', 'pwppr' ),
            'items2show' => 4,
            'isShowTitles' => 'on',
            'thumbW' => 145,
            'thumbH' => 145,
            'itemMargin' => 2,
            'imgSource' => 'attachedRand', // (attachedRand, attachedFirst, contentRand, contentFirst)
            'defImage' => '',
            'colorScheme' => 'green', 
            'buttonText' => 'Spin!', 
            'categories' => '', // empty=all
        );

    }

    /*
     * Outputs widget contents to visitors
     */
    public function widget( $args, $instance ) {

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) )
            echo $args['before_title']
               . apply_filters( 'widget_title', $instance['title'] )
               . $args['after_title'];

        $instance = wp_parse_args( (array) $instance, $this->defaults );

        echo '<div id="my-'.$this->id.'">';
            echo '<div class="pwppr-posts-container">';
            pwppr_posts( $instance ); 
            echo '</div>';
            ?>

            <?php
            wp_localize_script( 'pwppr-scripts', 'pwppr_'.$this->number, array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'pwppr_nonce' ),
                'id' => $this->id,
                'maxitems' => $this->maxitems,
                'items2show' => $instance['items2show'],
            ) );

            ?>

            <?php
            echo '<div class="pwpprRefreshCont" style="background-image:url('.PWPPR_HOME_URL.'images/spin-'.$instance['colorScheme'].'.gif);">
                      <div class="userInputCont" style="border: 2px solid '.$this->colorSchemes[ $instance['colorScheme'] ].'">
                        <input name="userNumPosts" class="userNumPosts" type="text"  
                             onClick="this.select();"
                             onkeyup="pwpprPostRefresh('.$this->number.'); return(false);" 
                             title="'.__('Enter posts count', 'pwppr').'"
                             value="'.$instance['items2show'].'" />
                             </div>
                      <div class="ajaxSpinBtn" 
                           onclick="pwpprPostRefresh('.$this->number.'); return(false);" 
                           ></div>
                      <div class="ajaxSquareBtn"
                           onclick="pwpprPostRefresh('.$this->number.'); return(false);" 
                           ><span>'.$instance['buttonText'].'</span></div>
                  </div>';

        echo '</div>'; //my-pwppr-xx

        echo $args['after_widget'];
    }

    /*
     * Validate and save settings
     */
    public function update( $new_instance, $old_instance ) {
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['items2show'] = absint($new_instance['items2show']);
		if ( $instance['items2show'] < 1 || $instance['items2show'] > $this->maxitems ) $instance['items2show'] = 4;
        $instance['isShowTitles'] = ( $new_instance['isShowTitles'] == 'on' ) ? $new_instance['isShowTitles'] : 'off';
        $instance['thumbW'] = ( absint( $new_instance['thumbW'] ) > 0 ) ? absint($new_instance['thumbW']) : $this->defaults['thumbW'];
        $instance['thumbH'] = ( absint( $new_instance['thumbH'] ) > 0 ) ? absint($new_instance['thumbH']) : $this->defaults['thumbH'];
        $instance['itemMargin'] = absint( $new_instance['itemMargin'] );
        if ( $new_instance['imgSource']=='attachedRand' 
          || $new_instance['imgSource']=='attachedFirst'
          || $new_instance['imgSource']=='contentRand'
          || $new_instance['imgSource']=='contentFirst') $instance['imgSource'] = $new_instance['imgSource'];
        else                                             $instance['imgSource'] = 'attachedRand';
        $instance['defImage'] = esc_url($new_instance['defImage']);
        $instance['colorScheme'] = $new_instance['colorScheme'];
        $instance['buttonText'] = esc_attr($new_instance['buttonText']);
        $instance['categories'] = $new_instance['categories'];

        return $instance;
    }

    /*
     * Outputs the options form to admins
     */
    public function form( $instance ) {
//echo '<pre>'.print_r($instance,1).'</pre>';

        $instance = wp_parse_args( (array) $instance, $this->defaults );
        extract( $instance ); // $title, $items2show, $isShowTitles, $thumbW, $thumbH, $itemMargin, $imgSource, $defImage, $colorScheme, $buttonText, $categories, 
        ?>

        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

		<p>
		<label for="<?php echo $this->get_field_id('items2show'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('items2show'); ?>" name="<?php echo $this->get_field_name('items2show'); ?>" type="text" value="<?php echo $items2show; ?>" size ="3" /> <small>(<?php echo __('maximum is ','pwppr').$this->maxitems; ?>)</small>
	    </p>

        <p>
        <input class="checkbox" type="checkbox" <?php checked( $isShowTitles, 'on' ); ?> id="<?php echo $this->get_field_id( 'isShowTitles' ); ?>" name="<?php echo $this->get_field_name( 'isShowTitles' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'isShowTitles' ); ?>"><?php _e( 'Show post titles', 'pwppr' ) ?></label>
        </p>

        <p>
        <?php _e( 'Thumbnail size:', 'pwppr' ); ?><br>
		<input id="<?php echo $this->get_field_id('thumbW'); ?>" name="<?php echo $this->get_field_name('thumbW'); ?>" type="text" value="<?php echo $thumbW; ?>" size ="3" />x<input id="<?php echo $this->get_field_id('thumbH'); ?>" name="<?php echo $this->get_field_name('thumbH'); ?>" type="text" value="<?php echo $thumbH; ?>" size ="3" /> px. <?php _e('with margin','pwppr');?> <input id="<?php echo $this->get_field_id('itemMargin'); ?>" name="<?php echo $this->get_field_name('itemMargin'); ?>" type="text" value="<?php echo $itemMargin; ?>" size ="1" /> px.
        </p>

        <p>
        <?php _e( 'Color scheme:', 'pwppr' ); ?>
        <select class="select" name="<?php echo $this->get_field_name( 'colorScheme' ); ?>">
            <?php
            foreach ( $this->colorSchemes as $colorName=>$nomatter ){
                echo '<option value="'.$colorName.'" '.selected($colorScheme,$colorName).'>' . ucfirst($colorName) . '</option>';
            }
            ?>
        </select>&nbsp;&nbsp;

        <?php _e( 'Button Title:', 'pwppr' ); ?>
		<input id="<?php echo $this->get_field_id('buttonText'); ?>" name="<?php echo $this->get_field_name('buttonText'); ?>" type="text" value="<?php echo $buttonText; ?>" size ="10" />
        </p>

        <p>
        <?php _e( 'Use:', 'pwppr' ); ?><br>
        <label><input class="radio" type="radio" value="attachedRand" <?php checked( $imgSource, 'attachedRand' ); ?> name="<?php echo $this->get_field_name( 'imgSource' ); ?>" /><?php _e('Random image from post attachments','pwppr'); ?></label><br>
        <label><input class="radio" type="radio" value="attachedFirst" <?php checked( $imgSource, 'attachedFirst' ); ?> name="<?php echo $this->get_field_name( 'imgSource' ); ?>" /><?php _e('First image from post attachments','pwppr'); ?></label><br>
        <label><input class="radio" type="radio" value="contentRand" <?php checked( $imgSource, 'contentRand' ); ?> name="<?php echo $this->get_field_name( 'imgSource' ); ?>" /><?php _e('Random image from post content','pwppr'); ?></label><br>
        <label><input class="radio" type="radio" value="contentFirst" <?php checked( $imgSource, 'contentFirst' ); ?> name="<?php echo $this->get_field_name( 'imgSource' ); ?>" /><?php _e('First image from post content','pwppr'); ?></label>
        </p>

        <p>
        <?php _e( 'Default image <small>(when no image was found)</small>:', 'pwppr' ); ?><br>
        <input class="widefat" id="<?php echo $this->get_field_id( 'defImage' ); ?>" name="<?php echo $this->get_field_name( 'defImage' ); ?>" type="text" value="<?php echo esc_url( $defImage ); ?>"><input class="addDefImageBtn button-secondary" type="button" value="<?php _e('Upload'); ?>" />
        </p>

        <p>
        <?php _e( 'Select categories:', 'pwppr' ); ?><br>
        <small>(<?php _e('Select nothing to use them all','pwppr'); ?>)</small><br>
        <div class="large-tax-box">
        <?php
        
        $args = array( 'hide_empty' => false, 'hierarchical' => false, 'fields' => 'all' );
        $cats = get_terms( 'category', $args );
        $roots = $childs = array();
        foreach ( $cats as $cat) {
            if ( ! $cat->parent )
                $roots[] = $cat; 
            else
                $childs[ $cat->parent ][] = $cat; 
        }
        $this->make_cat_tree ( $roots, $childs, $categories ); 
        ?>
        </div>
        </p>


        <?php
    }

    /*
     * Create hierarchical categories tree
     */
    private function make_cat_tree ( $root_arr, &$childs_arr, &$checked_cats ){
        echo '<ul>';
        foreach ( $root_arr as $cat ){
            echo '<li>'; ?>

            <label><input type="checkbox" value="<?php echo $cat->term_id; ?>" name="<?php echo $this->get_field_name( 'categories' ); ?>[]" <?php checked ( is_array( $checked_cats ) && in_array( $cat->term_id, $checked_cats ) ); ?> />
            <?php echo esc_html( $cat->name ); ?>
            </label>
            <?php

            if ( ! empty($childs_arr[$cat->term_id] ) )
                $this->make_cat_tree ( $childs_arr[$cat->term_id], $childs_arr, $checked_cats );
            
            echo '</li>';
        }
        echo '</ul>';
    }
}


add_action( 'widgets_init', create_function( '', 'return register_widget("PWP_Photoroulette_Widget");' ) );
