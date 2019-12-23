<?php
namespace Plugins\SimpleCRUD\Store;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDEntity;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStore;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreCredentials;
//use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreProvider;
//use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearch;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDStoreException;

class SimpleCRUDStore implements ISimpleCRUDStore
{
    private $_provider = null;
    private $_cache    = null;

    public function __construct(
        ?string                      $providerType = null,
        ?string                      $cacheType    = null,
        ?ISimpleCRUDStoreCredentials $credentials  = null
    )
    {
        if (empty($providerType)) {
            $errorMessage = 'SimpleCRUD Store Can Not Be Creared: '.
                            'Provider Type Is Not Set';
            throw new SimpleCRUDStoreException($errorMessage);
        }

        if (empty($cacheType)) {
            $errorMessage = 'SimpleCRUD Store Can Not Be Creared: '.
                            'Cache Type Is Not Set';
            throw new SimpleCRUDStoreException($errorMessage);
        }

        if (empty($credentials)) {
            $errorMessage = 'SimpleCRUD Store Can Not Be Creared: '.
                            'Credentials Is Not Set';
            throw new SimpleCRUDStoreException($errorMessage);
        }

        $this->_setProvider($providerType, $credentials);
        //$this->_setCacheInstance($providerIdent);
    }

    private function _setProvider(
        ?string                      $providerType = null,
        ?ISimpleCRUDStoreCredentials $credentials  = null
    ):  void
    {
        switch ($providerType) {
            case 'mysql':
                $this->_provider = $this->_getMySQLStoreProvider($credentials);
                break;

            default:
                $errorMessage = 'SimpleCRUD Store Provider '.
                                '"'.$providerType.'" Is Not Supported';
                throw new SimpleCRUDStoreException($errorMessage);
                break;
        }
    }

    private function _getMySQLStoreProvider(
        ?ISimpleCRUDStoreCredentials $credentials  = null
    ): \Plugins\SimpleCRUD\Store\Providers\SQL\MySQLStoreProvider
    {
        $this->_includeAbstactProvider();
        $this->_includeSQLProvider();
        $this->_includeMySQLProvider();
        return new \Plugins\SimpleCRUD\Store\Providers\SQL\MySQLStoreProvider(
            $credentials
        );
    }

    private function _includeAbstactProvider(): void
    {
        require_once __DIR__.'/providers/AbstractStoreProvider.php';
    }

    private function _includeSQLProvider(): void
    {
        require_once __DIR__.'/providers/sql/AbstractSQLStoreProvider.php';
    }

    private function _includeMySQLProvider(): void
    {
        require_once __DIR__.'/providers/sql/MySQLStoreProvider.php';
    }

    public function create(?ISimpleCRUDEntity $entity = null): bool
    {
        if (empty($entity)) {
            return false;
        }

        return $this->_provider->create($entity);
    }

    public function read(?ISimpleCRUDEntity $entity = null): ?array
    {
        if (empty($entity)) {
            return null;
        }

        $rows = $this->_provider->read($entity);

        if (empty($rows)) {
            return null;
        }

        return $rows;
    }

    public function update(?ISimpleCRUDEntity $entity = null): bool
    {
        if (empty($entity)) {
            return false;
        }

        return $this->_provider->update($entity);
    }

    public function delete(?ISimpleCRUDEntity $entity = null): bool
    {
        if (empty($entity)) {
            return false;
        }

        return $this->_provider->delete($entity);
    }
}
