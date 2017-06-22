<?php

namespace Komparu\Mongodb\Cache;


use Illuminate\Cache\StoreInterface;
use Illuminate\Encryption\Encrypter;
use Jenssegers\Mongodb\Connection;
use Jenssegers\Mongodb\Query\Builder;
use Komparu\Mongodb\Core\KomparuTaggableStore;
use Symfony\Component\Process\Exception\LogicException;

/**
 * Class MongodbTaggedStore
 * @package Komparu\Mongodb\Cache
 */
class MongodbTaggedStore extends KomparuTaggableStore implements StoreInterface
{

    const TAGS = 'tags';
    use MongoTrait;

    /**
     *
     */
    const KEY = '_id';

    /**
     * The encrypter instance.
     * @var Encrypter
     */
    protected $encrypter;

    /**
     * The name of the cache collection.
     * @var string
     */
    protected $collection_name;

    /**
     * A string that should be prepended to keys.
     * @var string
     */
    protected $prefix;

    /**
     * MongodbTaggedStore constructor.
     *
     * @param Connection $connection
     * @param Encrypter $encrypter
     * @param string $collection_name
     * @param string $prefix
     */
    public function __construct(Connection $connection, Encrypter $encrypter, $collection_name, $prefix)
    {
        $this->connection      = $connection;
        $this->encrypter       = $encrypter;
        $this->collection_name = $collection_name;
        $this->prefix          = $prefix;
    }

    /**
     * @param string $key
     *
     * @return null|string|void
     */
    public function get($key)
    {
        $prefixedKey = $this->getKey($key);

        $cache = $this->getWhere($prefixedKey)->first();


        if (!is_null($cache)) {
            if (time() >= $cache['expiration']->sec) {
                return $this->forget($key);
            }

            return unserialize($cache['value']);
            // speed improvement
            //return $this->encrypter->decrypt($cache['value']);
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $minutes
     *
     * @return bool
     */
    public function put($key, $value, $minutes)
    {
        $prefixedKey   = $this->getKey($key);
        $value = serialize($value);
        // speed improvement
        //$value = $this->encrypter->encrypt($value);

        $expiration = new \MongoDate($this->getTime() + ($minutes * 60));

        $data = array('expiration' => $expiration, self::KEY => $prefixedKey, 'value' => $value, self::TAGS => $this->getTags());

        $collection = $this->getCacheCollection();
        $item       = $collection->where(self::KEY, $prefixedKey)->first();

        try {
            if (is_null($item)) {
                $collection->insert($data);
            } else {
                $collection->where(self::KEY, $prefixedKey)->update($data);
            }

            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $key
     * @param int $value
     *
     * @return bool|int|void
     * @throws LogicException
     */
    public function increment($key, $value = 1)
    {
        throw new LogicException("Increment operations not supported by this driver.");
    }

    /**
     * @param string $key
     * @param int $value
     *
     * @return bool|int|void
     * @throws LogicException
     */
    public function decrement($key, $value = 1)
    {
        throw new \LogicException("Decrement operations not supported by this driver.");
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function forever($key, $value)
    {
        $this->put($key, $value, 5256000);
    }

    /**
     * @param string $key
     */
    public function forget($key)
    {
        $this->getWhere($key)->delete();
    }

    /**
     *
     */
    public function flush()
    {
        if (empty($this->getTags())) {
            $mongo_collection = $this->connection->getCollection($this->collection_name);

            $this->truncateCollection($mongo_collection);
        } else {
            $this->getWhere()->delete();
        }
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param $key
     *
     * @return string
     */
    private function getKey($key)
    {
        return $this->prefix . $key;
    }

    /**
     * @param $key
     *
     * @return Builder
     */
    private function getWhere($key = null)
    {        
        $prefixedKey = $this->getKey($key);
        $q = $this->getCacheCollection();

        $q = $key
            ? $q->where(self::KEY, $prefixedKey)
            : $q;
        $q = !empty($this->getTags())
            ? $q->whereIn(self::TAGS, $this->getTags())
            : $q;

        return $q;
    }
}
