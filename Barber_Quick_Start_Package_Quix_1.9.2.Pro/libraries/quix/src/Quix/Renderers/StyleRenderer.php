<?php

namespace ThemeXpert\Quix\Renderers;

use Assets;

class StyleRenderer extends NodeRenderer
{
    /**
     * Render note.
     *
     * @param $node
     *
     * @return string
     */
    public function renderNode($node)
    {
        $schema = array_find_by($this->nodes, 'slug', $node['slug']);

        if (!$schema) {
            return "/*node not found*/";
        }

        if ($this->isMobile && !$node['visibility']['xs']) {
            return "/* {$node['form']['advanced']['label']} hidden from mobile device */";
        } elseif ($this->isTablet && !$node['visibility']['sm']) {
            return "/* {$node['form']['advanced']['label']} hidden from tablet device */";
        }


        /**
         * FIXME: throw exception
         */
        if (!file_exists($schema['dynamic_style_file'])) {
            return "/*style file {$schema['dynamic_style_file']} does not exist*/";
        }

        $data = $this->getData($node, $schema);

        $override_file = QUIX_TEMPLATE_PATH . "/elements/" . $node['slug'] . "/style.php";

        if (file_exists($override_file)) {
            return $this->view->make($override_file, $data);
        }

        $data = $this->getData($node, $schema);

        $override_file = QUIX_TEMPLATE_PATH . "/overrides/" . $node['slug'] . "/style.php";
        if (file_exists($override_file)) {
            return $this->view->make($override_file, $data);
        }

        return $this->view->make($schema['dynamic_style_file'], $data);
    }

    /**
     * Render node.
     *
     * @param $nodes
     *
     * @return string
     */
    public function render($nodes, $item = null)
    {
        return implode("\n", $this->renderNodes($nodes));
    }
}
