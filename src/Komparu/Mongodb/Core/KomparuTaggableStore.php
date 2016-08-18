<?php

namespace Komparu\Mongodb\Core;

use Illuminate\Cache\StoreInterface;

abstract class KomparuTaggableStore implements StoreInterface
{

    public function tags($names)
	{
		return new KomparuTaggedCache($this, new KomparuTagSet($this, is_array($names) ? $names : func_get_args()));
	}

}