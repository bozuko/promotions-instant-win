<?php

class PromotionsInstantWin_Plugin extends Promotions_Plugin_Base
{
  
  public function init()
  {
    Snap::inst('PromotionsInstantWin_Admin');
    $this->register_field_groups(
      'instant-win', 'instant-win-messages'
    );
  }
  
  /**
   * @wp.filter       promotions/features
   */
  public function add_feature( $features )
  {
    $features['instant_win'] = 'Instant Win';
    return $features;
  }
  
  /**
   * @wp.filter       promotions/download/export_fields
   */
  public function add_instant_win_fields( $export, $post_id )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $post_id) )
      return $export;
    
    $export['entry.meta.instant_win'] = 'Instant Win ID';
    $export['entry.meta.instant_win_prize_name'] = 'Instant Win Name';
    return $export;
  }
  
  /**
   * @wp.action   promotions/analytics/register
   */
  public function register_analytics_metric( $analytics )
  {
    $analytics->register('instant_winners', array(
      'label'     => 'Instant Winners'
    ));
  }
  
  /**
   * @wp.action   promotions/analytics/weekly_statistics
   */
  public function register_weekly_stat( $weekly_stats, $promotion_id )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $promotion_id) ){
      return $weekly_stats;
    }
    $weekly_stats['instant_winners'] = 'Instant Winners';
    return $weekly_stats;
  }
  
  /**
   * @wp.action   promotions/analytics/statistics
   * @wp.priority 8
   */
  public function instant_win_stats( $promotion_id )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $promotion_id) ){
      return;
    }
    
    global $wpdb;
    
    $sql = <<<SQL
SELECT COUNT(*) FROM {$wpdb->posts}
  WHERE `post_type` = 'instant-win'
    AND `post_parent` = %d
SQL;
    
    $winners = Snap::inst('Promotions_Analytics')->get_all($promotion_id, 'instant_winners' );
    $total = $wpdb->get_var( $wpdb->prepare( $sql, $promotion_id ) );
    ?>
<h2 class="promotions-heading">Instant Win Statistics</h2>
<div class="big-stats">
  <div class="big-stat">
    <div class="number">
      <?= $winners ?> / <?= $total ?>
    </div>
    <div class="text">
      Instant Prizes Won
    </div>
  </div>
</div>
    <?php
  }
  
  /**
   * @wp.filter     promotions/tabs/promotion/register
   * @wp.priority   10
   */
  public function register_tab( $tabs )
  {
    $tabs['instantwin'] = 'Instant Win';
    return $tabs;
  }
  
  /**
   * @wp.filter     promotions/tabs/promotion/display
   * @wp.priority   10
   */
  public function display_tabs( $tabs, $post )
  {
    if( Snap::inst('Promotions_Functions')->is_enabled('instant_win', $post->ID) )
      return $tabs;
    unset( $tabs['instantwin'] );
    return $tabs;
  }
  
  /**
   * @wp.filter
   */
  public function upload_mimes( $mimes = array() )
  {
    // allow csv
    $mimes['csv'] = 'text/csv';
    return $mimes;
  }
  
   /**
   * @wp.filter         promotions/api/result?method=register
   */
  public function on_register( $result, $params )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $post->ID) )
      return $result;
    
    if( is_array($result) && isset($result['entry_id']) ){
      $result = $this->run( $result );
    }
    return $result;
  }
  
  /**
   * @wp.filter         promotions/api/result?method=enter
   */
  public function on_enter( $result )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $post->ID) )
      return $result;
    
    if( is_array($result) && isset($result['entry_id']) ){
      $result = $this->run( $result );
    }
    
    return $result;
  }
  
  protected function run( $result )
  {
    if( Snap::inst('Promotions_Functions')->is_enabled('demo') && !@$_REQUEST['_now'] ){
      $win = mt_rand(0,1);
      if( $win ){
        $prizes = get_field('instantwin_prizes');
        // grab a random one...
        $prize = $prizes[mt_rand(0, count($prizes)-1)];
        
        $result['instantwin'] = array(
          'win'       => true,
          'prize'     => array(
            'id'        => $prize['id'],
            'name'      => $prize['name']
          )
        );
        
        update_post_meta( $result['entry_id'], 'instant_win', $prize['id'] );
        update_post_meta( $result['entry_id'], 'instant_win_prize_name', $prize['name'] );
        
        Snap::inst('Promotions_Analytics')
          ->increment('instant_winners');
      }
      else {
        $result['instantwin'] = array(
          'win'       => false
        );
      }
    }
    else {
      $reg = get_post( get_post( $result['entry_id'] )->post_parent );
      $win = Snap::inst('PromotionsInstantWin_Engine')->run( get_the_ID(), $reg->ID );
      $result['instantwin'] = array('win' => false);
      if( $win ){
        
        $prizes = get_field('instantwin_prizes');
        $result['instantwin']['win'] = true;
        //$result['instantwin']['result'] = $win;
        $result['instantwin']['prize'] = array('id'=>$win->post_excerpt);
        
        Snap::inst('Promotions_Analytics')
          ->increment('instant_winners');
          
        update_post_meta( $result['entry_id'], 'instant_win', $win->post_excerpt );
        
        foreach( $prizes as $prize ){
          if( $prize['id'] == $win->post_excerpt ){
            update_post_meta( $result['entry_id'], 'instant_win_prize_name', $prize['name'] );
            $result['prize']['name'] = $prize['name'];
          }
        }
      }
    }
    return $result;
  }
  
  /**
   * @wp.filter   promotions/content/template
   * @wp.priority 20
   */
  public function iw_template( $template )
  {
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win') ){
      return $template;
    }
    $result = Snap::inst('Promotions_Core_Registration_Plugin')->get_result();
    if( !$result ) return $template;
    if( $result && isset($result['instantwin']) && $result['instantwin']['win'] ){
      return 'iw-win';
    }
    return 'iw-lose';
  }
}
