<?php

namespace ThemeXpert\Assets\Concerns;

trait HttpRequest
{
    /**
     * Determine mode type of the quix page.
     *
     * @return bool
     */
    protected function isPreviewMode()
    {
        if(isset($_GET['preview'])) {
            if($_GET['preview'] == true) return true;

            return false;
        }

        return false;
    }

    /**
     * Determine layout mode type of the quix page.
     *
     * @return bool
     */
    protected function isEditMode()
    {
        if(isset($_GET['layout'])) {
            if($_GET['layout'] == 'edit') return true;

            return false;
        }

        return false;
    }
}