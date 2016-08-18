<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;

class KomparuTagSet
{

    /**
     * @var StoreInterface
     */
    protected $store;

    /**
     * @var string[]
     */
    protected $names = [];

    /**
     * KomparuTagSet constructor.
     *
     * @param StoreInterface $store
     * @param string[] $names
     */
    public function __construct(StoreInterface $store, array $names)
    {
        $this->store = $store;
        $this->names = $names;
    }

}