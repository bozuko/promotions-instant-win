<?php

class PromotionsInstantWin_Plugin extends Promotions_Plugin_Base
{
  
  /**
   * @wp.action     promotions/init
   */
  public function promotions_init()
  {
    $this->register_field_groups(
      'instant-win'
    );
    // $this->register_data_dir(THEME_DIR.'/data');
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
    if( is_array($result) && isset($result['entry_id']) ){
      $result = $this->run( $result );
    }
    return $result;
  }
  
  protected function run( $result )
  {
    $result['instant_win'] = array(
      'win'       => rand(0,1) == 1
    );
    return $result;
  }
}