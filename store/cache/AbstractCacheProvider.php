<?php
namespace Plugins\SimpleCRUD\DataObject\CacheProviders;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDStoreCache;

abstract class AbstrastCacheProvider implements ISimpleCRUDStoreCache;
{
    const DEFAULT_LIFE_TIME = 1800;

    public function __construct(?int $lifeTime = null)
    {
        //To-Do
    }
}
