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
        $key = $this->getKey($key);

        $cache = $this->getWhere($key)->first();


        if (!is_null($cache)) {
            if (time() >= $cache['expiration']->sec) {
                return $this->forget($key);
            }

            return $this->encrypter->decrypt($cache['value']);
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $minutes
     */
    public function put($key, $value, $minutes)
    {
        $key   = $this->getKey($key);
        $value = $this->encrypter->encrypt($value);

        $expiration = new \MongoDate($this->getTime() + ($minutes * 60));

        $data = array('expiration' => $expiration, self::KEY => $key, 'value' => $value, 'tags' => $this->getTags());

        $item = $this->getCacheCollection()->where(self::KEY, $key)->first();

        if (is_null($item)) {
            $this->getCacheCollection()->insert($data);
        } else {
            $this->getCacheCollection()->where(self::KEY, $key)->update($data);
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
            $mongo_collection->drop();
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
        $q = $this->getCacheCollection();

        $q = $key
            ? $q->where(self::KEY, $key)
            : $q;
        $q = !empty($this->getTags())
            ? $q->whereIn('trags', $this->getTags())
            : $q;

        return $q;
    }
}