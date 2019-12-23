<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDContent
{
    public function getType(): string;

    public function getStatus(): bool;

    public function isJSON(): bool;

    public function isHTML(): bool;

    public function getJSON(): string;

    public function setStatus(bool $status = false);

    public function setError(?string $errorMessage = null);

    public function setDataJSON(array $dataJSON = []);

    public function setDataHTML(?array $dataHTML = null);

    public function getHTML();

    public function setTypeHTML();

    public function setTypeJSON();
}
