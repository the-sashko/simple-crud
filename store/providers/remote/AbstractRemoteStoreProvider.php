<?php

namespace Plugins\SimpleCRUD\Store\Providers\Remote;

abstract class AbstractRemoveStoreProvider
    extends    AbstractStoreProvider
    implements ISimpleCRUDStoreProvider
{
    abstract public function sendToRemote(): bool;

    abstract public function getFromRemote(): bool;

    abstract public function read(?array $search = null): ?array;

    abstract public function update(
        ?array $values = null,
        ?array $search = null
    ): bool;

    abstract public function delete(?string $search = null): bool;

    abstract public function count(?string $search = null): int;

    abstract public function isExists(?string $search = null): bool;
}
