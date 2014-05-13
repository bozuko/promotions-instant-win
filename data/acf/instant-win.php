<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
    'id' => 'acf_instant-win',
    'title' => 'Instant Win',
    'fields' => array (
      array (
        'key' => 'field_5372472bcb967',
        'label' => 'Prizes',
        'name' => 'instantwin_prizes',
        'type' => 'repeater',
        'instructions' => 'Instant win is managed at a per prize level.',
        'sub_fields' => array (
          array (
            'key' => 'field_53724760cb968',
            'label' => 'Name',
            'name' => 'name',
            'type' => 'text',
            'required' => 1,
            'column_width' => '',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
          ),
          array (
            'key' => 'field_53724f155a615',
            'label' => 'Unique ID',
            'name' => 'id',
            'type' => 'text',
            'required' => 1,
            'column_width' => '',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'html',
            'maxlength' => '',
          ),
          array (
            'key' => 'field_5372476ecb969',
            'label' => 'Generate',
            'name' => 'generate',
            'type' => 'true_false',
            'column_width' => '',
            'message' => 'Generate winning times',
            'default_value' => 0,
          ),
          array (
            'key' => 'field_537247c7cb96a',
            'label' => 'Total',
            'name' => 'generate_total',
            'type' => 'number',
            'conditional_logic' => array (
              'status' => 1,
              'rules' => array (
                array (
                  'field' => 'field_5372476ecb969',
                  'operator' => '==',
                  'value' => '1',
                ),
              ),
              'allorany' => 'all',
            ),
            'column_width' => '',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => 1,
            'max' => '',
            'step' => '',
          ),
          array (
            'key' => 'field_53724937cb96d',
            'label' => 'Generation Algorithm',
            'name' => 'generation_algorithm',
            'type' => 'select',
            'conditional_logic' => array (
              'status' => 1,
              'rules' => array (
                array (
                  'field' => 'field_5372476ecb969',
                  'operator' => '==',
                  'value' => '1',
                ),
              ),
              'allorany' => 'all',
            ),
            'column_width' => '',
            'choices' => array (
              'random' => 'Random',
              'equal' => 'Equally Distributed',
            ),
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
          ),
          array (
            'key' => 'field_53724873cb96c',
            'label' => 'Upload Timestamps',
            'name' => 'generate_file',
            'type' => 'file',
            'column_width' => '',
            'save_format' => 'object',
            'library' => 'all',
          ),
        ),
        'row_min' => '',
        'row_limit' => '',
        'layout' => 'row',
        'button_label' => 'Add Prize',
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
    