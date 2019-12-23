<?php

namespace Plugins\SimpleCRUD\Store\Providers\File;

abstract class AbstractFileStoreProvider
    extends    AbstractsStoreProvider
    implements ISimpleCRUDStoreProvider
{
    private function _isFileExists(): bool
    {
        //To-Do

        return false;
    }

    public function getFileContent(): ?string
    {
        //To-Do

        return null;
    }

    public function saveFileContent(): bool
    {
        //To-Do

        return false;
    }

    abstract public function serialize(?string $value = null): array;

    abstract public function deserialize(?array $value = null): ?string;

    abstract public function read(?array $search = null): ?array;

    abstract public function update(
        ?array $values = null,
        ?array $search = null
    ): bool;

    abstract public function delete(?string $search = null): bool;

    abstract public function count(?string $search = null): int;

    abstract public function isExists(?string $search = null): bool;
}
