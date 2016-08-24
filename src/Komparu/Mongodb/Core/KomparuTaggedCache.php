<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;
use Illuminate\Cache\TaggedCache;

class KomparuTaggedCache extends TaggedCache implements StoreInterface
{

    /**
     * @var KomparuTaggableStore
     */
    protected $store;

    /**
     * @var KomparuTagSet
     */
    protected $tags;

    /**
     * KomparuTaggedCache constructor.
     *
     * @param KomparuTaggableStore $store
     * @param KomparuTagSet $tags
     */
    public function __construct(KomparuTaggableStore $store, KomparuTagSet $tags)
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
        $this->getTaggedStore()->get($key);
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
        $this->getTaggedStore()->put($key, $value, $minutes);
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
        $this->getTaggedStore()->decrement($key, $value);
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
        $this->getTaggedStore()->forever($key, $value);
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
        $this->getTaggedStore()->forget($key);
    }

    /**
     * Remove all items from the cache.
     * @return void
     */
    public function flush()
    {
        $this->getTaggedStore()->flush();
    }

    /**
     * Get the cache key prefix.
     * @return string
     */
    public function getPrefix()
    {
        $this->getTaggedStore()->getPrefix();
    }


    /**
     * @return KomparuTaggableStore
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @return KomparuTagSet
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return KomparuTaggableStore
     */
    private function getTaggedStore()
    {
        return $this
            ->getStore()
            ->_setTagsForNextOperation($this->getTags()->getNames());
    }

}