<?php

class PromotionsInstantWin_Plugin extends Promotions_Plugin_Base
{
  
  public function init()
  {
    Snap::inst('PromotionsInstantWin_Admin');
    $this->register_field_groups(
      'instant-win'
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
    if( Snap::inst('Promotions_Functions')->is_enabled('demo') ){
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
        $result['instantwin']['win'] = true;
        $result['instantwin']['result'] = $win;
        $result['instantwin']['prize'] = array('id'=>$win->post_excerpt);
      }
    }
    return $result;
  }
}