<?php

namespace ThemeXpert\Quix\Library;

use ThemeXpert\FileSystem\FileSystem;

class Library
{
    /**
     * Store templates.
     *
     * @var array
     */
    protected $templates = [];

    /**
     * Store ignored groups.
     *
     * @var array
     */
    protected $ignoredGroups = [];

    /**
     * Store paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Fill paths.
     *
     * @param       $path
     * @param       $url
     * @param array $groups
     */
    public function fill($path, $url, $groups = [])
    {
        $this->paths[] = compact("path", "url", "groups");
    }

    /**
     * Add template to the templates array.
     *
     * @param       $file
     * @param       $url
     * @param array $groups
     */
    public function add($file, $url, $groups = [])
    {
        $config = json_decode(file_get_contents($file), true);
        if (!is_array($config)) {
            return;
        }

        $filename = basename($file);

        if (!array_key_exists('screenshot', $config)) {
            $imageName = str_replace('.json', '.jpg', $filename);
            $config['screenshot'] = trailingslashit($url) . $imageName;
        }

        if (!array_key_exists('groups', $config)) {
            $config['groups'] = [];
        }

        if (!array_key_exists('id', $config)) {
            $config['id'] = $filename;
        }

        if (!is_array($config['groups'])) {
            $config['groups'] = (array)$config['groups'];
        }

        $config['groups'] = array_merge($config['groups'], (array)$groups);

        $this->templates[] = $config;
    }

    /**
     * Load all templates.
     */
    public function loadAll()
    {
        $this->templates = [];

        foreach ($this->paths as $path) {
            $files = FileSystem::files($path['path'], 'json');

            foreach ($files as $file) {
                $config_file = untrailingslashit($path['path']) . DIRECTORY_SEPARATOR . $file;
                $this->add($config_file, $path['url'], $path['groups']);
            }
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        $this->loadAll();
        $ignoredGroups = $this->getIgnoredGroups();

        return array_filter($this->templates, function ($template) use ($ignoredGroups) {
            return !count(array_intersect($template['groups'], $ignoredGroups));
        });
    }

    /**
     * Get ignore groups.
     *
     * @return array
     */
    public function getIgnoredGroups()
    {
        return $this->ignoredGroups;
    }

    /**
     * Set ignore groups.
     *
     * @param array $ignoredGroups
     */
    public function setIgnoredGroups($ignoredGroups)
    {
        $this->ignoredGroups = array_unique(array_merge((array)$ignoredGroups, $this->ignoredGroups));
    }
}
