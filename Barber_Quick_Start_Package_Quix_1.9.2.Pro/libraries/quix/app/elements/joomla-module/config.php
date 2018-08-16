<?php
if( !function_exists('getJoomlaModulesList') ){
  function getJoomlaModulesList() {
    JModelLegacy::addIncludePath( JPATH_SITE . '/administrator/components/com_modules/models', 'ModulesModel' );

    // Get an instance of the generic articles model
    $model = JModelLegacy::getInstance( 'Modules', 'ModulesModel', [ 'ignore_request' => true ] );

    // Set the filters based on the module params
    $model->setState( 'list.start', 0 );
    $model->setState( 'list.limit', 9999 );
    
    // Access filter
    // $access = ! JComponentHelper::getParams( 'com_modules' )->get( 'show_noauth' );
    // $model->setState( 'filter.access', $access );
    $model->setState( 'filter.state', 1 );

    // Set ordering
    $model->setState( 'list.ordering', 'a.ordering' );

    $model->setState( 'list.direction', 'ASC' );

    // Retrieve Content
    $items = $model->getItems();
    
    return $items;
  }  
}

$modules = array_reduce( getJoomlaModulesList(), function ( $carry, $module ) {
  $carry[$module->id] = $module->title;

  return $carry;
}, [ ] );

return [
  'slug' => 'joomla-module',
  'name' => 'Joomla Module',
  'groups' => ['joomla', 'content'],
  'form' => [
    'general' => [
      [ 
        'name' => 'module_id', 
        'type' => 'select',  
        'label' => 'Select Module',
        'options' => $modules 
      ],
      [
        'name' => 'alignment',
        'type' => 'select',
        'value' => 'left',
        'image' => true,
        'responsive', true,
        'options' =>[
          'left' => 'Left',
          'center' => 'Center',
          'right' => 'Right',
          'justify' => 'Justify'
        ]
      ],
      [
        'name' => 'margin',
        'type' => 'margin'
      ],
      [
        'name' => 'padding',
        'type' => 'padding'
      ]
    ],
  ],

];
