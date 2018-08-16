<?php

namespace ThemeXpert\Quix;

use Doctrine\Common\Cache\Cache as DoctrineCache;

class Cache
{
    /**
     * Instance of doctrine.
     *
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * Cache lift time.
     *
     * @var int
     */
    protected $cacheLifeTime;

    /**
     * Determine cache enable/disable.
     *
     * @var bool
     */
    private $shouldCache;

    /**
     * Create a new instance of cache.
     *
     * @param \Doctrine\Common\Cache\Cache $cache
     * @param int                          $cacheLifeTime
     * @param bool                         $shouldCache
     */
    public function __construct(DoctrineCache $cache, $cacheLifeTime, $shouldCache)
    {
        $this->cache = $cache;

        $this->cacheLifeTime = $cacheLifeTime;

        $this->shouldCache = $shouldCache;

        if (array_get($_GET, 'clear_cache')) {
            $this->clearCache();
        }
    }

    /**
     * Set cache life time.
     *
     * @param int $cacheLifeTime
     *
     * @return Application
     */
    public function setCacheLifeTime($cacheLifeTime)
    {
        $this->cacheLifeTime = $cacheLifeTime;

        return $this;
    }

    /**
     * Delete all cache from the registered cache list.
     */
    public function clearCache()
    {
        $this->cache->deleteAll();
    }

    /**
     * Get cache details by ID.
     *
     * @param $id
     *
     * @return mixed
     */
    public function fetch($id)
    {
        $args = func_get_args();

        if (count($args) === 1) {
            return $this->cache->fetch($id);
        } else {
            $callback = $args[1];

            return $this->getCacheById($id, $callback);
        }
    }

    /**
     * Get Cache by id.
     *
     * @param $id
     * @param $callback
     *
     * @return mixed
     */
    protected function getCacheById($id, $callback)
    {
        if (!$this->shouldCache) {
            return $callback();
        } else {
            if ($this->cache->contains($id)) {
                return $this->cache->fetch($id);
            } else {
                $data = $callback();
                $this->cache->save($id, $data, $this->cacheLifeTime);

                return $data;
            }
        }
    }
}
