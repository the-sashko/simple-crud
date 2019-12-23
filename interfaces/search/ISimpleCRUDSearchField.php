<?php
namespace Plugins\SimpleCRUD\Interfaces\Search;

interface ISimpleCRUDSearchField
{
    public function getCondition(): ?string;

    public function getField(): ?string;

    public function getValue(): ?string;
}
