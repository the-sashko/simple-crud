<?php
namespace Plugins\SimpleCRUD\Interfaces;

use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreCredentials;

interface ISimpleCRUDConfig
{
    public function isReturnResult(): bool;

    public function getStoreType(): ?string;

    public function getCacheType(): ?string;

    public function getStoreCredentials(): ?ISimpleCRUDStoreCredentials;

    public function getSalt(): ?string;
}
