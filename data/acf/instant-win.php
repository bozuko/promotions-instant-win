<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_instant-win',
    'title' => 'Instant Win',
    'fields' => array (
      array (
        'key' => 'field_5355fdb8e9d88',
        'label' => 'Enable Instant Win',
        'name' => 'enable_instant_win',
        'type' => 'true_false',
        'message' => '',
        'default_value' => 0,
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'promotion_tab',
          'operator' => '==',
          'value' => 'instantwin',
          'order_no' => 0,
          'group_no' => 0,
        ),
      ),
    ),
    'options' => array (
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array (
      ),
    ),
    'menu_order' => 0,
  ));
}
    