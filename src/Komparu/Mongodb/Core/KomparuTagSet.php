<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;
use Illuminate\Cache\TagSet;

class KomparuTagSet extends TagSet
{

    /**
     * @var StoreInterface
     */
    protected $store;

    /**
     * @var string[]
     */
    protected $names = [];

}