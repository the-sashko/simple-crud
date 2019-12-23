<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDStoreCacheProvider
{
    public function get(?array $values = null): bool;

    public function set(?array $search = null): ?array;

    public function check(?array $search = null): bool;

    public function remove(?string $search = null): bool;

    public function removeAll(?string $search = null): bool;

    public function getCredentials(): ISimpleCRUDStoreCredentials;

    public function getDriver(): ?ISimpleCRUDStoreDriver;
}
