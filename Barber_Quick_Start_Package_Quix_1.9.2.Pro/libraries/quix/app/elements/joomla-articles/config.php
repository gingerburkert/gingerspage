<?php

if( ! class_exists('QuixJoomlArticleElementClass') )
{
  include_once ( __DIR__ . '/helper.php' );
}

$info = new QuixJoomlArticleElementClass();
$cats = array_reduce( $info->getInput(), function ( $carry, $module ) {
  $carry[$module->value] = $module->text;
  return $carry;
}, [ ] );


return [
  'slug' => 'joomla-articles',
  'name' => 'Joomla Articles',
  'groups' => ['joomla', 'content'],
  'form' => [
    'general' => [
      [ 'name' => 'category',
        'type' => 'select',
        'label' => 'Select Category',
        'options' => $cats
      ],

      [ 'name' => 'count',
        'type' => 'text',
        'value' => 5,
        'help'=> 'Num of articles you want to show'
      ],

      [ 'name' => 'show_featured',
        'type' => 'select',
        'label' => 'Featured Articles',
        'options' => [
          'show' => JText::_('JSHOW'),
          'hide' => JText::_('JHIDE'),
          'only' => 'Only featured'
        ]
      ],

      [ 'name' => 'show_child_category_articles',
        'type' => 'switch',
        'label' => 'Child Category Articles',
        'help'=> 'Show/Hide child category articles',
        'value'=> true],

      [
        'name' => 'article_ordering',
        'type' => 'select',
        'label' => 'Article Ordering',
        'value' => 'a.title',
        'options' => [
          'publish_up' => 'Latest',
          'a.hits' => 'Popular',
          'a.ordering' => 'Article order',
          'a.title' => 'Title'
        ]
      ],

      [
        'name' => 'article_ordering_direction',
        'type' => 'select',
        'label' => 'Ordering Direction',        
        'value' => 'ASC',
        'options' => [
          'DESC' => 'Descending',
          'ASC' => 'Ascending'
        ]
      ],

      [
        'name' => 'alignment',
        'type' => 'select',
        'value' => 'left',
        'label' => 'Text Alignment',
        'image' => true,
        'responsive' => true,
        'options' => [
          'left' => 'Left',
          'center' => 'Center',
          'right' => 'Right',
        ]
      ],
    ],

    'styles' => [
      [
        'name' => 'title_styles',
        'type' => 'divider'
      ],
      [ 'name' => 'link_titles',
        'type' => 'switch',
        'label' => 'Link Titles',
        'value'=> true
      ],

      [ 'name' => 'title_font',
        'type' => 'typography',
        'label' => 'Font'
      ],

      [ 'name' => 'title_color',
        'type' => 'color',
        'label' => 'Text Color'
      ],

      [ 'name' => 'title_margin',
        'type' => 'margin',
        'label' => 'Margin'
      ],
      [
        'name' => 'item_body_styles',
        'type' => 'divider'
      ],

      [
        'name' => 'bg_color',
        'type' => 'color',
        'label' => 'Background Color'
      ],
      [
        'name' => 'bg_margin',
        'type' => 'margin',
        'label' => 'Margin'
      ],

      [
        'name' => 'bg_padding',
        'type' => 'padding',
        'label' => 'Padding'
      ],

      [
        'name' => 'element_styles',
        'type' => 'divider'
      ],
      [
        'name' => 'layout',
        'type' => 'select',
        'value' => 'list',
        'image' => true,
        'options' =>[
          'list' => 'List',
          'grid' => 'Grid'
        ]
      ],

      [
        'name' => 'column',
        'type' => 'select',
        'value' => 3,
        'options' =>[
          12 => '1 Column',
          6 => '2 Columns',
          4 => '3 Columns',
          3 => '4 Columns'
        ],
        'depends' => [ 'layout' => 'grid' ]
      ],

      [
        'name' => 'image_size',
        'type' => 'slider',
        'max' => '1000',
        'value' => '250',
        'suffix' => 'px',
        'depends' => [ 'layout' => 'list' ]
      ],

      [ 
        'name' => 'show_meta_icon',
        'type' => 'switch',
        'label' => 'Show Meta Icons',
        'help' => 'Icons for metadata such as - date, category, user etc'
      ],

      [ 'name' => 'show_date',
        'type' => 'switch',
        'label' => 'Show Date',
        'help' => 'Display article creation date',
        'value'=> false
      ],
      
      [ 'name' => 'date_format',
        'type' => 'text',
        'label' => 'Date format',
        'help' => 'Date format for article date',
        'value'=> 'd, M Y',
        'depends' => [ 'show_date' => true ]
      ],

      [ 'name' => 'show_image',
        'type' => 'switch',
        'label' => 'Show Image',
        'value'=> true
      ],

      [ 'name' => 'show_category',
        'type' => 'switch',
        'label' => 'Show Category Name',
        'value'=> false
      ],

      [ 'name' => 'show_author',
        'type' => 'switch',
        'value'=> false
      ],

      [ 'name' => 'show_introtext',
        'type' => 'switch',
        'value'=> false
      ],

      [ 'name' => 'introtext_font',
        'type' => 'typography',
        'label' => 'Font',
        'depends' => [
          'show_introtext' => true
        ]
      ],

      [ 'name' => 'introtext_color',
        'type' => 'color',
        'label' => 'Text Color',
        'depends' => [
          'show_introtext' => true
        ]
      ],

      [ 'name' => 'introtext_margin',
        'type' => 'margin',
        'label' => 'Margin',
        'depends' => [
          'show_introtext' => true
        ]
      ],

      [ 'name' => 'introtext_limit',
        'type' => 'slider',
        'max' => '500',
        'suffix' => 'char',
        'value' => '100',
        'label' => 'Character Limit',
        'depends'=>['show_introtext'=> true]
      ],
     
      [
        'name' => 'margin',
        'type' => 'margin'
      ],

      [
        'name' => 'padding',
        'type' => 'padding'
      ],

      [
        'name' => 'button_styles',
        'type' => 'divider',
        'label' => 'Readmore Button Style'
      ],

      [ 'name' => 'show_readmore',
        'type' => 'switch',
        'value'=> false,
        'label' => 'Readmore Button'
      ],
      
      [ 'name' => 'readmore_style',
        'type' => 'select',
        'value' => 'qx-btn-default',
        'options' => [
          'qx-btn-default' => 'Default',
          'qx-btn-primary' => 'Primary',
          'qx-btn-success' => 'Success',
          'qx-btn-info' => 'Info',
          'qx-btn-warning' => 'Warning',
          'qx-btn-danger' => 'Danger',
          'qx-btn-link' => 'Link',
        ],
        'depends'=>['show_readmore'=> true]
      ],

      [ 'name' => 'readmore_text',
        'type' => 'text',
        'value' => 'Read More...',
        'depends'=>['show_readmore'=> true]
      ],

      [ 'name' => 'readmore_font',
        'type' => 'typography',
        'label' => 'Font',
        'depends'=>['show_readmore'=> true]
      ],

      [
        'name' => 'box_shadow_styles',
        'type' => 'divider'
      ],
      [
        'name' => 'box_shadow',
        'type' => 'switch',
        'label' => 'Enable Box Shadow'
      ],
      [
        'name' => 'box_shadow_color',
        'type' => 'color',
        'label' => 'Color',
        'depends' =>[ 'box_shadow' => true ]
      ],
      [
        'name' => 'box_shadow_blur',
        'type' => 'slider',
        'label' => 'Blur',
        'max' => '200',
        'suffix' => 'px',
        'depends' => [ 'box_shadow' => true ]
      ],
      [
        'name' => 'box_shadow_spread',
        'type' => 'slider',
        'label' => 'Spread',
        'max' => '200',
        'suffix' => 'px',
        'depends' => [ 'box_shadow' => true ]
      ],
      [
        'name' => 'box_shadow_horizontal',
        'type' => 'slider',
        'label' => 'Horizontal',
        'min' => '-250',
        'max' => '250',
        'suffix' => 'px',
        'depends' => [ 'box_shadow' => true ]
      ],
      [
        'name' => 'box_shadow_vertical',
        'type' => 'slider',
        'label' => 'Vertical',
        'min' => '-250',
        'max' => '250',
        'suffix' => 'px',
        'depends' => ['box_shadow' => true ]
      ],
      [
        'name' => 'box_shadow_inset',
        'type' => 'switch',
        'label' => 'Inset',
        'help' => 'If specified, the shadows are drawn inside the frame.',
        'depends' => [ 'box_shadow' => true ]
      ],
    ]
  ]
];
