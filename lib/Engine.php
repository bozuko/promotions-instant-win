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
        
        WHERE `post_status` != 'claimed'
          AND `post_date` < %s
          AND `post_type` = 'instant-win'
          AND `post_parent` = %d
        
        ORDER BY `post_date` ASC
        
        LIMIT 1
    ";
    
    $key = $time.mt_rand(0,10000).$registration_id;
    
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
  
  public function generate_from_file( $file, $id, $promotion_id )
  {
    $timestamps = array();
    $fh = fopen( $file, 'r' );
    while( ($row = fgetcsv( $fh )) !== false ){
      // assume its the first row.
      $time = strtotime( $row[0] );
      $timestamps[] = date('Y-m-d H:i:s', $time);
    }
    fclose( $fh );
    $this->create_timestamps( $timestamps, $id, $promotion_id );
  }
  
  public function generate( $count, $id, $promotion_id, $algorithm='random' )
  {
    $start = Snap::inst('Promotions_Functions')->get_start( $promotion_id );
    $end = Snap::inst('Promotions_Functions')->get_end( $promotion_id );
    
    $fn = 'generate_'.$algorithm;
    $stamps = array();
    if( method_exists( $this, $fn ) ){
      $stamps = $this->$fn( $count, $start, $end );
    }
    $this->create_timestamps( $stamps, $id, $promotion_id );
  }
  
  protected function generate_random( $count, $start, $end )
  {
    $start_timestamp = $start->getTimestamp()+1;
    $end_timestamp = $end->getTimestamp()-1;
    
    $stamps = array();
    
    for( $i=0; $i<$count; $i++ ){
      $timestamp = mt_rand( $start_timestamp+1, $end_timestamp-1 );
      $stamps[] = date('Y-m-d H:i:s', $timestamp );
    }
    
    return $stamps;
  }
  
  protected function generate_equal( $count, $start, $end )
  {
    $start_timestamp = $start->getTimestamp()+1;
    $end_timestamp = $end->getTimestamp()-1;
    
    $stamps = array();
    
    $interval = floor( ($end_timestamp - $start_timestamp) / $count );
    
    for( $i=0; $i<$count; $i++ ){
      $stamps[] = date('Y-m-d H:i:s', $start_timestamp + ($interval*$i));
    }
    
    return $stamps;
  }
  
  protected function create_timestamps( $ar, $id, $promotion_id )
  {
    global $wpdb;
    
    $this->clear( $id, $promotion_id );
    
    foreach( $ar as $i => $stamp ){
      wp_insert_post(array(
        'post_type'     => 'instant-win',
        'post_status'   => 'publish',
        'post_parent'   => $promotion_id,
        'post_date'     => $stamp,
        'post_title'    => $id,
        'post_excerpt'  => $id,
        'post_name'     => $id.'-'.$i
      ));
    }
  }
  
  public function clear( $id, $promotion_id )
  {
    global $wpdb;
    $sql = "
      DELETE FROM `{$wpdb->postmeta}`
        WHERE `post_id` IN (
        SELECT ID FROM {$wpdb->posts} `p`
          WHERE `p`.`post_type` = 'instant-win'
            AND `p`.`post_parent` = %d
            AND `p`.`post_title` = %s )
    ";
    $stmt = $wpdb->prepare( $sql, $promotion_id, $id );
    $wpdb->query( $stmt );
    
    $sql = "
      DELETE FROM {$wpdb->posts}
        WHERE `post_type` = 'instant-win'
          AND `post_parent` = %d
          AND `post_title` = %s
    ";
    $stmt = $wpdb->prepare( $sql, $promotion_id, $id );
    return $wpdb->query( $stmt );
  }
}