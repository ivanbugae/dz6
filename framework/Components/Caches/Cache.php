<?php

namespace framework\Components\Caches;

use framework\Components\Interfaces\CacheInterface;

class Cache implements CacheInterface
{

    public function memory(array $data = null)
    {
        print_r($data);
    }
}