<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;
use Illuminate\Cache\TaggableStore;

abstract class KomparuTaggableStore extends TaggableStore implements StoreInterface
{
    /**
     * @var null|string[]
     */
    protected $tags;

    public function tags($names)
    {
        return new KomparuTaggedCache($this, new KomparuTagSet($this, is_array($names) ? $names : func_get_args()));
    }

    /**
     * Set tags list for a single operation.
     * Note: any get/save/delete operation will reset tags list even it is not using them
     *
     * @param string[] $tags
     *
     * @return $this
     */
    public function _setTagsForNextOperation(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }


}