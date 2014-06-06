<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_instant-win-messages',
    'title' => 'Instant Win Messages',
    'fields' => array (
      array (
        'key' => 'field_538f5e8ab1b6a',
        'label' => 'Instant Win Winning Message',
        'name' => 'message_iw_win',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
      array (
        'key' => 'field_538f5ea0b1b6b',
        'label' => 'Instant Win Losing Message',
        'name' => 'message_iw_lose',
        'type' => 'wysiwyg',
        'default_value' => '',
        'toolbar' => 'full',
        'media_upload' => 'yes',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'messages',
          'order_no' => 0,
          'group_no' => 0,
        ),
        array (
          'param' => 'promotion_feature',
          'operator' => '==',
          'value' => 'instant_win',
          'order_no' => 1,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'no_box',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 2,
  ));
}
    