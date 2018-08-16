<?php

namespace ThemeXpert\Quix\Renderers;

use Mobile_Detect;
use ThemeXpert\View\View;
use ThemeXpert\Quix\Renderers\Contracts\NodeRendererInterface;

class NodeRenderer implements NodeRendererInterface
{
    /**
     * Instance of view.
     *
     * @var \ThemeXpert\View\View
     */
    protected $view;

    /**
     * Store form.
     *
     * @var mixed
     */
    protected $form;

    /**
     * Create a new instance of node reanderer.
     *
     * @param View          $view
     * @param Mobile_Detect $detect
     * @param               $nodes
     */
    public function __construct(View $view, Mobile_Detect $detect, $nodes)
    {
        $this->view = $view;

        $this->detect = $detect;

        $this->nodes = $nodes;

        $this->isTablet = $this->detect->isTablet();

        $this->isMobile = $this->detect->isMobile();

        $Mobile_Detect = new Mobile_Detect();
        if($Mobile_Detect->isTablet()){
            $device = 'tablet';
        }elseif($Mobile_Detect->isMobile()){
            $device = 'mobile';
        }else{
            $device = 'all';
        }

        $this->device = $device;
    }

    /**
     * Render node.
     *
     * @param $node
     *
     * @return string
     */
    public function renderNode($node)
    {
        $schema = array_find_by($this->nodes, 'slug', $node['slug']);

        if (!$schema) {
            return "<!----node not found---->";
        }
        
        // print_r($this->device);die;
        switch ($this->device) {
            case 'tablet':
                
                // switch ($node['slug']) {
                //     case 'section':
                //     case 'row':
                //     case 'column':
                //         break;
                //     default:
                //         // print_r($node);die;        
                //         break;
                // }

                if (!$node['visibility']['sm'] and !$node['visibility']['md']) {
                    return "<!--- {$node['form']['advanced']['label']} hidden from tablet device ---!>";
                }
                break;

            case 'mobile':
                if (!$node['visibility']['xs']) {
                    return "<!--- {$node['form']['advanced']['label']} hidden from mobile device ---!>";
                }
                break;            
            case 'all':
            default:
                // continue
                break;
        }

        // if ($this->isMobile && !$node['visibility']['xs']) {
        //     return "<!--- {$node['form']['advanced']['label']} hidden from mobile device ---!>";
        // } elseif ($this->isTablet && !$node['visibility']['sm']) {
        //     return "<!--- {$node['form']['advanced']['label']} hidden from tablet device ---!>";
        // }

        /**
         * FIXME: throw exception
         */
        if (!file_exists($schema['view_file'])) {
            return "<!--view file {$schema['view_file']} does not exist-->";
        }

        $data = $this->getData($node, $schema);

        $override_file = QUIX_TEMPLATE_PATH . "/elements/" . $node['slug'] . "/view.php";

        if (file_exists($override_file)) {
            return $this->view->make($override_file, $data);
        }

        $override_file = QUIX_TEMPLATE_PATH . "/overrides/" . $node['slug'] . "/view.php";


        if (file_exists($override_file)) {
            return $this->view->make($override_file, $data);
        }

        return $this->view->make($schema['view_file'], $data);
    }

    /**
     * Get data
     *
     * FIXME: CACHE THIS
     *
     * @param $node
     * @param $schema
     *
     * @return array
     */
    public function getData($node, $schema)
    {
        $field = flatten_array(array_get($node, 'form', []));

        $field = $this->merge_data($field, flatten_array(array_get($schema, 'form', [])));

        $visibility = array_get($node, 'visibility', []);

        return [
            'renderer' => $this,
            'title' => array_get($field, 'title', null),
            'id' => array_get($field, 'id', null),
            'type' => array_get($node, 'slug', null),
            'size' => array_get($node, 'size', []),
            'visibility' => $visibility,
            'visibilityClasses' => visibilityClasses($visibility),
            'field' => $field,
            'node' => $node,
        ];
    }

    /**
     * Merge data.
     *
     * @param $data
     * @param $form
     *
     * @return array
     */
    protected function merge_data($data, $form)
    {
        $form = array_reduce($form, function ($carry, $control) {
            $carry[$control['name']] = $control['value'];

            return $carry;
        }, []);

        $data = array_merge_recursive_distinct($form, $data);

        return $data;
    }

    /**
     * Render single node.
     *
     * @param $nodes
     *
     * @return string
     */
    public function render($nodes, $item = null)
    {
        return implode("", $this->renderNodes($nodes));
    }

    /**
     * Render nodes.
     *
     * @param $nodes
     *
     * @return array
     */
    public function renderNodes($nodes)
    {
        return array_map([$this, 'renderNode'], $nodes);
    }

    /**
     * Set form.
     *
     * @param $form
     */
    protected function setForm($form)
    {
        $this->form = $form;
    }
}
