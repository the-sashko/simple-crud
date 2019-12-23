<?php
namespace Plugins\SimpleCRUD\DataObject\CacheProviders;

abstract class AbstrastCacheProvider
{
    const DEFAULT_LIFE_TIME = 1800;

    public function __construct(?int $lifeTime = null)
    {
        //To-Do
    }
}
