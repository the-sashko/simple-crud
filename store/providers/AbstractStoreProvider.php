<?php
namespace Plugins\SimpleCRUD\Store\Providers;

use Plugins\SimpleCRUD\Interfaces\Store\Providers\ISimpleCRUDStoreProvider;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDtoreCredentials;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreDriver;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDAbstractStoreProviderException;

abstract class AbstractStoreProvider implements ISimpleCRUDStoreProvider
{
    private $_credentials = null;

    private $_driver = null;

    public function __construct(
        ISimpleCRUDStoreCredentials $credentials = null
    )
    {
        if (empty($credentials)) {
            $errorMessage = 'SimpleCRUD Store Provider Can Not Be Created: '.
                            'Missing Credentials';
            throw new SimpleCRUDAbstractStoreProviderException($errorMessage);
        }

        $this->_credentials = $credentials;

        $this->setDriver();
    }

    public function getCredentials(): ISimpleCRUDStoreCredentials
    {
        return $this->_credentials;
    }

    public function getDriver(): ?ISimpleCRUDStoreDriver
    {
        return $this->_driver;
    }

    //abstract public function count(?string $search = null): int;

    //abstract public function isExists(?string $search = null): bool;

    abstract public function setDriver(): void;
}
