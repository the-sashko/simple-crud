<?php
namespace Plugins\SimpleCRUD\Store\Providers\Drivers;

use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreDriver;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreCredentials;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDPDODriverException;

class PDODriver implements ISimpleCRUDStoreDriver
{
    private $_PDOInstance = null;

    public function __construct(
        string                      $dbType,
        ISimpleCRUDStoreCredentials $credentials
    )
    {
        $host     = $credentials->getHost();
        $port     = $credentials->getPort();
        $dbName   = $credentials->getDataBase();
        $user     = $credentials->getUser();
        $password = $credentials->getPassword();

        $dsn = "{$dbType}:host={$host};port={$port};dbname={$dbName}";

        $this->_dbConnect($dsn, $user, $password);
    }

    public function __destruct()
    {
        $this->_PDOInstance = NULL;
    }

    private function _dbConnect(
        ?string $dsn      = '',
        ?string $user     = '',
        ?string $password = ''
    ): void
    {
        if (empty($dsn)) {
            $errorMessage = 'Could not connect to database! '.
                            'DSN Has Invalid Format';

            throw new SimpleCRUDPDODriverException($errorMessage);
        }

        if (empty($user)) {
            $errorMessage = 'Could not connect to database! '.
                            'User Has Invalid Format';

            throw new SimpleCRUDPDODriverException($errorMessage);
        }

        if (empty($password)) {
            $errorMessage = 'Could not connect to database! '.
                            'Password Has Invalid Format';

            throw new SimpleCRUDPDODriverException($errorMessage);
        }

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];

        try {
            $this->_PDOInstance = new \PDO($dsn, $user, $password, $options);
        } catch (\PDOException $error) {
            $errorMessage = 'Could not connect to database! '.
                            'Error: "'.$error.'"';

            $this->_dbError($error);
        }
    }

    public function selectQuery(?string $sql = null) : array
    {
        if (empty($sql)) {
            return [];
        }

        try {
            return (array) $this->_PDOInstance->query($sql)->fetchALL();
        } catch (\PDOException $exp) {
            $errorMessage = 'SQL query failed! '.
                            'Error: "'.$exp->getMessage().'" '.
                            'Query: "'.$sql.'"';

            throw new SimpleCRUDPDODriverException($errorMessage);
        }
    }

    public function query(?string $sql = null) : bool
    {
        if (empty($sql)) {
            return false;
        }

        try {
            return (bool) $this->_PDOInstance->query($sql);
        } catch (PDOException $error) {
            $errorMessage = 'SQL query failed! '.
                            'Error: "'.$error->getMessage().'" '.
                            'Query: "'.$sql.'"';

            throw new SimpleCRUDPDODriverException($errorMessage);
        }
    }

    public function transactionStart() : bool
    {
        $sql = 'START TRANSACTION;';

        return $this->query($sql);
    }

    public function transactionCommit() : bool
    {
        $sql = 'COMMIT;';

        return $this->query($sql);
    }

    public function transactionRollback() : bool
    {
        $sql = 'ROLLBACK;';

        return $this->query($sql);
    }
}
