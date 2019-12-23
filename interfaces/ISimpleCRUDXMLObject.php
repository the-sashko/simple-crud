<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDXMLObject
{
    public function getTableName(): string;

    public function getPrimaryField(): string;

    public function getItemsOnPage(): int;

    public function getFields(): array;

    public function getSearch(): array;

    public function getAction(?string $actionType = null): ?array;
}
