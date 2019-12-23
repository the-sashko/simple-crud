<?php
namespace Plugins\SimpleCRUD\Interfaces\Store\Action;

interface ISimpleCRUDAction
{
    public function execute(): void;

    public function executeHandlers(): bool;
}
