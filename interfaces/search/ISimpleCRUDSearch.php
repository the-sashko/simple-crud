<?php
namespace Plugins\SimpleCRUD\Interfaces\Search;

interface ISimpleCRUDSearch
{
    public function getCondition(): ?string;

    public function getNodes(): ?array;

    public function getFields(): ?array;
}