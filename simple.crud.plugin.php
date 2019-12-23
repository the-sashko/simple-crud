<?php
namespace Plugins\SimpleCRUD;

use Plugins\SimpleCRUD\Interfaces\ISimpleCrudContent;
use Plugins\SimpleCRUD\Interfaces\ISimpleCrudPugin;

use Plugins\SimpleCRUD\Tables\BaseTable;
use Plugins\SimpleCRUD\XML\SimpleCRUDXMLObject;

class SimpleCRUDPlugin implements ISimpleCRUDPugin
{
    const DEFAULT_ERROR_MESSAGE = 'Unknown SimpleCRUD Error';
    const DEFAULT_RAND_MIN_VALUE = 0;
    const DEFAULT_RAND_MAX_VALUE = 200000;

    const ACTION_LIST   = 'list';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_REMOVE = 'remove';

    private $_table = null;

    private $_config = null;

    private $_action = null;

    private $_CSRFToken = null;

    private $_formData = [];

    private $_page = 1;

    public function init(
        ?string $xmlFilePath    = null,
        ?string $configFilePath = null,
        array   $formData       = [],
        int     $page           = 1
    ): void
    {
        try {
            $this->_action = static::ACTION_LIST;

            $this->_page = $page > 0 ? $page : 1;

            $this->_setConfig($configFilePath);

            $this->_loadFormData($formData);

            $this->_checkCSRFToken();
            $this->_setCSRFToken();

            $this->_setTable($xmlFilePath);
        } catch (\Exception $exp) {
            $this->_error($exp->getMessage());
        }
    }

    public function execute(): string
    {
        try {
            $content = new CrudContent();

            switch ($this->_action) {
                case static::ACTION_LIST:
                    $this->_table->executeActionList($content);
                    break;
                case static::ACTION_CREATE:
                    $this->_table->executeActionCreate($content);
                    break;
                case static::ACTION_UPDATE:
                    $this->_table->executeActionUpdate($content);
                    break;
                case static::ACTION_REMOVE:
                    $this->_table->executeActionRemove($content);
                    break;
                default:
                    $errorMessage = 'Invalid SimpleCRUD Action: "'.
                                    $this->_action.'"';
                    $this->_error($errorMessage);
                    break;
            }
        } catch (\Exception $exp) {
            $this->_error($exp->getMessage());
        }

        if (!$this->_config->isReturnResult()) {
            $this->_renderContent($content);
        }

        return $this->_getContent($content);
    }

    private function _getContent(ISimpleCRUDContent $content): string
    {
        if ($content->isJSON()) {
            return (string) $content->getJSON();
        }

        return (string) $content->getHTML();
    }

    private function _renderContent(ISimpleCRUDContent $content): void
    {
        if ($content->isJSON()) {
            $this->_renderJSON($content->getJSON());
        }

        echo $content->getHTML();

        exit(0);
    }

    private function _setTable(?string $xmlFilePath = null):  void
    {
        if (empty($xmlFilePath)) {
            $this->_error('SimpleCRUD XML File Is Not Set!');
        }

        $crudXML      = new SimpleCRUDXMLObject($xmlFilePath);
        $this->_table = new BaseTable(
            $crudXML,
            $this->_config,
            $this->_formData
        );
    }

    private function _setConfig(?string $configFilePath = null):  void
    {
        if (empty($configFilePath)) {
            $this->_error('SimpleCRUD Config File Is Not Set!');
        }

        $this->_config = new SimpleCRUDConfig($configFilePath);
    }

    private function _loadFormData(?array $formData = null): bool
    {
        if (empty($formData)) {
            return false;
        }

        if (array_key_exists('_action', $formData)) {
            $this->_action = (string) $formData['_action'];
        }

        if (array_key_exists('_csrf', $formData)) {
            $this->_CSRFToken = (string) $formData['_csrf'];
        }

        if (!array_key_exists('data', $formData)) {
            $this->_formData = $formData['data'];
        }

        return true;
    }

    private function _checkCSRFToken(): bool
    {
        $this->_startSession();

        if ($this->_action == static::ACTION_LIST) {
            $this->_formData  = [];
            $this->_CSRFToken = null;

            return true;
        }

        if (
            array_key_exists('_csrf', $_SESSION) &&
            $this->_CSRFToken == $_SESSION['_csrf']
        ) {
        }

        $this->_error('Invalid CSRF Token!');
    }

    private function _error(?string $errorMessage = null): void
    {
        if (empty($errorMessage)) {
            $errorMessage = status::DEFAULT_ERROR_MESSAGE;
        }

        $dataJSON = [
            'status' => false,
            'data'   => [
                'error' => $errorMessage
            ]
        ];

        $this->_renderJSON(json_encode($dataJSON));
    }

    private function _renderJSON(?string $dataJSON = null): void
    {
        if (empty($dataJSON)) {
            $dataJSON = '{}';
        }

        header('Content-Type: application/json');

        echo $dataJSON;

        exit(0);
    }

    private function _setCSRFToken(): bool
    {
        $this->_startSession();

        $CSRFToken = $this->_generateCSRFToken();

        $this->_CSRFToken  = $CSRFToken;
        $_SESSION['_csrf'] = $CSRFToken;

        return true;
    }

    private function _startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function _generateCSRFToken(): string
    {
        if (empty($this->_config->getSalt())) {
            $this->_error('SimpleCRUD Config Salt Is Not Set!');
        }

        $randomString = md5(rand(
            static::DEFAULT_RAND_MIN_VALUE,
            static::DEFAULT_RAND_MIN_VALUE
        ));

        return hash('sha512', $randomString.$this->_config->getSalt().time());
    }
}
