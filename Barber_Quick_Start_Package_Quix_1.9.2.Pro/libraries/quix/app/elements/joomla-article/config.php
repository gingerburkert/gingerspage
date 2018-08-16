<?php
if( ! class_exists('QuixJoomlSingleArticleElementClass') )
{
  include_once ( __DIR__ . '/helper.php' );
}

$articles = array_reduce( QuixJoomlSingleArticleElementClass::getListJoomlaArticle(), function ( $carry, $article ) {
  $carry[$article->id] = $article->title;
  return $carry;
}, [ ] );

return [
  'slug' => 'joomla-article',
  'name' => 'Joomla Article',
  'groups' => ['joomla', 'content'],
  'form' => [
    'general' => [
      [ 
        'name' => 'article_id', 
        'type' => 'select',  
        'label' => 'Select Article',
        'options' => $articles 
      ],
      [
        'name' => 'show_title',
        'type' => 'switch',
        'label'=> 'Show Title',
        'value'=> true
      ],
      [
        'name' => 'title_tag',
        'type' => 'select',
        'label'=> 'Title tag',
        'value' => 'h3',
        'options' =>[
          'h1' => 'h1',
          'h2' => 'h2',
          'h3' => 'h3',
          'h4' => 'h4',
          'h5' => 'h5',
          'h6' => 'h6'
        ],
        'depends' => [ 'show_title' => true ]
      ],
      [
        'name' => 'show_content',
        'type' => 'switch',
        'label'=> 'Show Content',
        'value'=> true
      ],
      [
        'name' => 'content_type',
        'type' => 'select',
        'label'=> 'Content Type',
        'value' => 'introtext',
        'options' => [
          'all' => 'Full Article',
          'introtext' => 'Intro Text',
          'fulltext' => 'Full Text'
        ],
        'depends' => [ 'show_content' => true ]
      ],
      [
        'name' => 'strip_tags',
        'type' => 'switch',
        'label'=> 'Strip tags',
        'value' => true,
        'help' => 'Remove all html tags from intro content.',
        'depends' => [ 'show_content' => true ]
      ],
      [
        'name' => 'show_image',
        'type' => 'switch',
        'label'=> 'Intro Image'
      ],
      [
        'name' => 'image_alignment',
        'type' => 'select',
        'label'=> 'Image alignment',
        'value' => 'left',
        'responsive' => true,
        'options' =>[
          'left' => 'Left',
          'right' => 'Right',
          'none' => 'None'
        ],
        'depends' => [ 'show_image' => true ]
      ],
      [
        'name' => 'show_readmore',
        'type' => 'switch',
        'label'=> 'Readmore Button'
      ],
      [
        'name' => 'readmore_text',
        'type' => 'text',
        'label'=> 'Button Text',
        'value'=> 'Readmore...',
        'depends' => [ 'show_readmore' => true ]
      ],
      [
        'name' => 'readmore_class',
        'type' => 'select',
        'label'=> 'Button Type',
        'value'=> 'qx-btn-default',
        'options' => [
          'qx-btn-default ' => 'Default',
          'qx-btn-primary ' => 'Primary',
          'qx-btn-success ' => 'Success',
          'qx-btn-info ' => 'Info',
          'qx-btn-warning ' => 'Warning',
          'qx-btn-danger ' => 'Danger',
          'qx-btn-link ' => 'Link'
        ],
        'depends' => [ 'show_readmore' => true ]
      ]
    ],
    'styles' => [
      [
        'name' => 'title_styles',
        'type' => 'divider'
      ],
      [
        'name' => 'title_font',
        'type' => 'typography',
        'label' => 'Font'
      ],
      [
        'name' => 'title_color',
        'type' => 'color',
        'label' => 'Color'
      ],
      [
        'name' => 'element_styles',
        'type' => 'divider'
      ],
      [
        'name' => 'alignment',
        'type' => 'select',
        'value' => 'left',
        'label' => 'Text Alignment',
        'image' => true,
        'responsive' => true,
        'options' =>[
          'left' => 'Left',
          'center' => 'Center',
          'right' => 'Right',
          'justify' => 'Justify'
        ]
      ],
      [
        'name' => 'body_font',
        'type' => 'typography',
        'label' => 'Font'
      ],
      [
        'name' => 'body_font_color',
        'type' => 'color',
        'label' => 'Text Color'
      ],
      [
        'name' => 'bg_color',
        'type' => 'color',
        'label' => 'Background Color'
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
      ]
    ]
  ],

];
