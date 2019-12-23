<?php
namespace Plugins\SimpleCRUD\Store\Providers\SQL;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDEntity;
use Plugins\SimpleCRUD\Store\Providers\AbstractStoreProvider;
use Plugins\SimpleCRUD\Store\Providers\Drivers\PDODriver;
use Plugins\SimpleCRUD\Interfaces\Store\Providers\ISimpleCRUDStoreSQLProvider;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDAbstractSQLStoreProviderException;

use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearch;

abstract class AbstractSQLStoreProvider
    extends    AbstractStoreProvider
    implements ISimpleCRUDStoreSQLProvider
{
    const DB_TYPE = null;

    //protected $_PDOInstance = bool;

    /*public function __construct($credentials  = null)
    {
        parent::__construct($credentials);

        $this->initDriver();
    }*/

    public function setDriver(): void
    {
        $credentials = $this->getCredentials();

        if (empty($credentials)) {
            $errorMessage = 'SimpleCRUD Store Credentials Is Not Set';
            throw new SimpleCRUDAbstractSQLStoreProviderException(
                $errorMessage
            );
        }

        require_once __DIR__.'/../../drivers/PDODriver.php';

        $this->_driver = new PDODriver(static::DB_TYPE, $credentials);
    }


    public function create(?ISimpleCRUDEntity $entity): bool
    {
        if (empty($entity)) {
            return false;
        }

        die('create');

        return $this->_provider->create($values);
    }

    public function read(?ISimpleCRUDEntity $entity): ?array
    {
        if (empty($entity)) {
            return null;
        }

        $tableName = $entity->getName();
        $tableAlias = $entity->getAlias();

        if (empty($tableAlias)) {
            $tableAlias = $tableName;
        }

        if (empty($tableAlias)) {
            $errorMessage = 'SimpleCRUD Select Table Name Is Not Set';
            throw new SimpleCRUDAbstractSQLStoreProviderException(
                $errorMessage
            );
        }

        $sqlSearch = $this->prepareSearch($entity);

        $sqlSelectFields = $this->prepareSelectFields($entity);

        $sql = "
            SELECT DISTINCT {$sqlSelectFields}
            FROM   {$tableName} AS {$tableAlias}
            WHERE  {$sqlSearch};
        ";

        $rows = $this->_driver->selectQuery($sql);

        return $rows;
    }

    public function update(?ISimpleCRUDEntity $entity): bool
    {
        if (empty($entity)) {
            return false;
        }

        die('update');

        return $this->_provider->update($values, $search);
    }

    public function delete(?ISimpleCRUDEntity $entity):  bool
    {
        if (empty($entity)) {
            return false;
        }

        die('delete');

        return $this->_provider->delete($search);
    }

    /*private function _connect(): bool
    {
        //To-Do
        return false;
    }

    private function getPDOInstance(): bool
    {
        //To-Do
        return false;
    }

    abstract public function prepareSearch(?array $search = null): string;

    abstract public function create(?array $values = null): bool;

    abstract public function read(?array $search = null): ?array;

    abstract public function update(
        ?array $values = null,
        ?array $search = null
    ): bool;

    abstract public function delete(?string $search = null): bool;

    abstract public function count(?string $search = null): int;

    abstract public function isExists(?string $search = null): bool;*/

    abstract public function prepareSearch(
        ?ISimpleCRUDEntity $entity = null
    ):  string;

    abstract public function prepareSelectFields(
        ?ISimpleCRUDEntity $entity = null
    ):  string;
}
