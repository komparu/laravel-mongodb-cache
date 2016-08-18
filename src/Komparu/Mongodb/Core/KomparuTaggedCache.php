<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;
use Illuminate\Cache\TaggedCache;

class KomparuTaggedCache extends TaggedCache implements StoreInterface
{

    /**
     * @var StoreInterface
     */
    protected $store;

    /**
     * @var KomparuTagSet
     */
    protected $tags;

    /**
     * KomparuTaggedCache constructor.
     *
     * @param StoreInterface $store
     * @param KomparuTagSet $tags
     */
    public function __construct(StoreInterface $store, KomparuTagSet $tags)
    {
        return parent::__construct($store, $tags);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $this->store->get($key);
    }


    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed $value
     * @param  int $minutes
     *
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        // TODO: Implement put() method.
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        // TODO: Implement increment() method.
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        // TODO: Implement decrement() method.
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed $value
     *
     * @return void
     */
    public function forever($key, $value)
    {
        // TODO: Implement forever() method.
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     *
     * @return void
     */
    public function forget($key)
    {
        // TODO: Implement forget() method.
    }

    /**
     * Remove all items from the cache.
     * @return void
     */
    public function flush()
    {
        // TODO: Implement flush() method.
    }

    /**
     * Get the cache key prefix.
     * @return string
     */
    public function getPrefix()
    {
        // TODO: Implement getPrefix() method.
    }
}