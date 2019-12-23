<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDPugin
{
    public function init(
        ?string $xmlFilePath    = null,
        ?string $configFilePath = null,
        array   $formData       = [],
        int     $page           = 1
    ):  void;

    public function execute(): ?string;
}
