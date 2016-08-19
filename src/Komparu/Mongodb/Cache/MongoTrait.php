<?php

namespace Komparu\Mongodb\Cache;


use Jenssegers\Mongodb\Connection;
use Jenssegers\Mongodb\Query\Builder;

trait MongoTrait
{
    /**
     * The database connection instance.
     * @var Connection
     */
    protected $connection;

    /**
     * Get the underlying database connection.
     * @return \MongoClient
     */
    public function getConnection()
    {
        return $this->connection->getMongoClient();
    }

    /**
     * Get the encrypter instance.
     * @return \Illuminate\Encryption\Encrypter
     */
    public function getEncrypter()
    {
        return $this->encrypter;
    }

    /**
     * Get the cache key prefix.
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Get the collection.
     * @return Builder
     */
    protected function getCacheCollection()
    {
        return $this->connection->collection($this->collection_name);
    }

    /**
     * Get the current system time.
     * @return int
     */
    protected function getTime()
    {
        return time();
    }
}