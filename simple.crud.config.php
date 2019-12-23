<?php
namespace Plugins\SimpleCRUD;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDConfig;
use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStoreCredentials;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDConfigException;
use Plugins\SimpleCRUD\Store\SimpleCRUDStoreCredentials;

class SimpleCRUDConfig implements ISimpleCRUDConfig
{
    private $_isReturnResult = false;

    private $_storeType = null;

    private $_cacheType = null;

    private $_salt = null;

    private $_storeCredentials = null;

    public function __construct(?string $configFilePath = null)
    {
        if (empty($configFilePath)) {
            $errorMessage = 'SimpleCRUD Config File Is Not Set';
            throw new SimpleCRUDConfigException($errorMessage);
        }

        $this->_parseConfigFile($configFilePath);
    }

    public function isReturnResult(): bool
    {
        return $this->_isReturnResult;
    }

    public function getStoreType(): ?string
    {
        if (empty($this->_storeType)) {
            return null;
        }

        return $this->_storeType;
    }

    public function getCacheType(): ?string
    {
        if (empty($this->_cacheType)) {
            return null;
        }

        return $this->_cacheType;
    }

    public function getStoreCredentials(): ?ISimpleCRUDStoreCredentials
    {
        if (empty($this->_storeCredentials)) {
            return null;
        }

        return $this->_storeCredentials;
    }

    public function getSalt(): ?string
    {
        if (empty($this->_salt)) {
            return null;
        }

        return $this->_salt;
    }

    private function _parseConfigFile(?string $configFilePath = null): void
    {
        $configData = $this->_getConfigDataFromFile($configFilePath);

        if (empty($configData)) {
            $errorMessage = 'CRUD Config File Is Missing '.
                            'Or Has Invalid Format';
            throw new SimpleCRUDConfigException($errorMessage);
        }

        $this->_validateConfigData($configData);

        $this->_isReturnResult   = (bool) $configData['return_result'];
        $this->_salt             = (string) $configData['salt'];
        $this->_storeType        = (string) $configData['store']['type'];
        $this->_cacheType        = (string) $configData['store']['cache'];
        $this->_storeCredentials = new SimpleCRUDStoreCredentials(
            $configData['store']['credentials']
        );
    }

    private function _validateConfigData(array $configData): void
    {
        $this->_validateConfigDataParam('return_result', $configData);
        $this->_validateConfigDataParam('salt', $configData);
        $this->_validateConfigDataParam('store', $configData);
        $this->_validateConfigDataParam('type', (array) $configData['store']);
        $this->_validateConfigDataParam('cache', (array) $configData['store']);
        $this->_validateConfigDataParam(
            'credentials',
            (array) $configData['store']
        );
    }

    private function _validateConfigDataParam(
        string $param,
        array  $configData
    ):  void
    {
        if (
            !array_key_exists($param, $configData) &&
            empty($configData[$param])
        ) {
            $errorMessage = 'CRUD Config Value "'.$param.'" Is Not Set';
            throw new SimpleCRUDConfigException($errorMessage);
        }
    }

    private function _getConfigDataFromFile(
        ?string $configFilePath = null
    ):  ?array
    {
        if (empty($configFilePath)) {
            return null;
        }

        if (!file_exists($configFilePath)) {
            return null;
        }

        if (!is_file($configFilePath)) {
            return null;
        }

        return (array) json_decode(file_get_contents($configFilePath), true);
    }
}
