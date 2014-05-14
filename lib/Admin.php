<?php

class PromotionsInstantWin_Admin extends Snap_Wordpress_Plugin
{
  /**
   * @wp.action         acf/save_post
   * @wp.priority       200
   */
  public function import_timestamps( $post_id )
  {
    if( get_post_type( $post_id ) != 'promotion' ) return;
    if( !Snap::inst('Promotions_Functions')->is_enabled('instant_win', $post_id ) ) return;
    
    $prizes = get_field('instantwin_prizes', $post_id);
    foreach( $prizes as $i => $prize ){
      if( $prize['generate_file'] ){
        $id = $prize['generate_file']['id'];
        $path = get_attached_file( $id );
        Snap::inst('PromotionsInstantWin_Engine')->generate_from_file($path, $prize['id'], $post_id );
        wp_delete_post( $id );
      }
      else if( $prize['generate'] ){
        // check for total
        $total = $prize['generate_total'];
        $algorithm = $prize['generation_algorithm'];
        Snap::inst('PromotionsInstantWin_Engine')->generate($total, $prize['id'], $post_id, $algorithm );
        
        //update_sub_field(array('instantwin_prizes', $i, 'generate'), false);
        update_post_meta( $post_id, "instantwin_prizes_{$i}_generate", false );
      }
    }
    return $post_id;
  }
}
