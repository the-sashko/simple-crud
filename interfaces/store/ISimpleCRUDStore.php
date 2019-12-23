<?php
namespace Plugins\SimpleCRUD\Interfaces\Store;

interface ISimpleCRUDStore
{
    public function create(?ISimpleCRUDEntity $entity = null): bool;

    public function read(?ISimpleCRUDEntity $entity = null): ?array;

    public function update(?ISimpleCRUDEntity $entity = null): bool;

    public function delete(?ISimpleCRUDEntity $entity = null): bool;
}
