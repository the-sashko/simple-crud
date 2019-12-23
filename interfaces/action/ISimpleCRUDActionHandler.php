<?php
namespace Plugins\SimpleCRUD\Interfaces\Store\Action;

interface ISimpleCRUDActionHandler
{
    public function getPluginInstance(): string;

    public function getMethodName(): string;
}

