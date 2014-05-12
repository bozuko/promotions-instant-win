<?php

class PromotionsInstantWin_Engine
{
  public function run( $promotion_id, $registration_id )
  {
    
    global $wpdb;
    
    $time = microtime();
    
    $sql = "
    
      UPDATE `{$wpdb->posts}` `p`
        SET `post_status` = 'claimed',
            `post_name` = %s
            `post_title` = %s
            `post_content` = %s
        
        WHERE `post_status` = 'publish'
          AND `post_date` < %s
          AND `post_type` = 'instant-win'
          AND `post_parent` = %d
        
        ORDER BY `post_date` ASC
        
        LIMIT 1
    ";
    
    $key = $time.rand(0,10000).$registration_id;
    
    $date = Snap::inst('Promotions_Functions')->now()->format('Y-m-d H:i:s');
    $stmt = $wpdb->prepare( $sql, $key, $key, $registration_id, $date, $promotion_id );
    
    if( !$wpdb->query( $stmt ) ){
      return false;
    }
    
    // okay... lets find that winner!
    $posts = get_posts(array(
      'post_name' => $key
    ));
    
    if( is_array( $posts ) && count( $posts ) ){
      $post = $posts[0];
      update_post_meta($post->ID, 'registration_id', $registration_id);
      return $post;
    }
    
    return false;
    
  }
}