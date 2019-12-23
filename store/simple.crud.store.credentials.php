<?php
namespace Plugins\SimpleCRUD\Store;

use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreCredentials;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDStoreCredentialsException;

class SimpleCRUDStoreCredentials implements ISimpleCRUDStoreCredentials
{
    private $_host = null;

    private $_port = null;

    private $_dataBase = null;

    private $_file = null;

    private $_user = null;

    private $_password = null;

    private $_token = null;

    public function __construct(?array $credentialsData = null)
    {
        if (empty($credentialsData)) {
            $errorMessage = 'SimpleCRUD Store Credentials Has Invalid Format';
            throw new SimpleCRUDStoreCredentialsException($errorMessage);
        }

        $host     = $this->_getParam('host', $credentialsData);
        $port     = $this->_getParam('port', $credentialsData);
        $dataBase = $this->_getParam('db', $credentialsData);
        $file     = $this->_getParam('file', $credentialsData);
        $user     = $this->_getParam('user', $credentialsData);
        $password = $this->_getParam('password', $credentialsData);
        $token    = $this->_getParam('token', $credentialsData);

        $this->_host     = empty($host) ? null : $host;
        $this->_port     = empty($port) ? null : (int) $port;
        $this->_dataBase = empty($dataBase) ? null : $dataBase;
        $this->_file     = empty($file) ? null : $file;
        $this->_user     = empty($user) ? null : $user;
        $this->_password = empty($password) ? null : $password;
        $this->_token    = empty($token) ? null : $token;
    }

    public function _getParam(string $param, array $credentialsData): ?string
    {
        if (!array_key_exists($param, $credentialsData)) {
            return null;
        }

        if (empty($credentialsData[$param])) {
            return null;
        }

        return (string) $credentialsData[$param];
    }

    public function getHost(): ?string
    {
        return $this->_host;
    }

    public function getPort(): ?int
    {
        return $this->_port;
    }

    public function getDataBase(): ?string
    {
        return $this->_dataBase;
    }

    public function getFile(): ?string
    {
        return $this->_file;
    }

    public function getUser(): ?string
    {
        return $this->_user;
    }

    public function getPassword(): ?string
    {
        return $this->_password;
    }

    public function getToken(): ?string
    {
        return $this->_token;
    }
}
