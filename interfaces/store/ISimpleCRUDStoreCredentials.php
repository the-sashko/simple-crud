<?php
namespace Plugins\SimpleCRUD\Interfaces\Store;

interface ISimpleCRUDStoreCredentials
{
    public function getHost(): ?string;

    public function getPort(): ?int;

    public function getDataBase(): ?string;

    public function getFile(): ?string;

    public function getUser(): ?string;

    public function getPassword(): ?string;

    public function getToken(): ?string;
}
