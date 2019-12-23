<?php
namespace Plugins\SimpleCRUD\XML;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDXMLObject;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDXMLObjectException;

class SimpleCRUDXMLObject implements ISimpleCRUDXMLObject
{
    const DEFAULT_ITEMS_ON_PAGE_VALUE = 25;

    private $_xmlParser;

    private $_tableName = null;

    private $_primaryField = null;

    private $_itemsOnPage = null;

    private $_fields = null;

    private $_search = null;

    private $_actionList = null;

    private $_actionCreate = null;

    private $_actionInfo = null;

    private $_actionUpdate = null;

    private $_actionRemove = null;

    public function __construct(?string $xmlFilePath = null)
    {
        if (empty($xmlFilePath)) {
            $errorMessage = 'SimpleCRUD XML File Is Not Set';
            throw new SimpleCRUDXMLObjectException($errorMessage);
        }

        $this->_xmlParser = new SimpleCRUDXMLParser($xmlFilePath);

        $this->_setValues();
    }

    public function getTableName(): string
    {
        if (empty($this->_tableName)) {
            $errorMessage = 'SimpleCRUD XML Table Name Is Not Set';
            throw new SimpleCRUDXMLObjectException($errorMessage);
        }

        return $this->_tableName;
    }

    public function getPrimaryField(): string
    {
        if (empty($this->_primaryField)) {
            $errorMessage = 'SimpleCRUD XML Primary Field Is Not Set';
            throw new SimpleCRUDXMLObjectException($errorMessage);
        }

        return $this->_primaryField;
    }

    public function getItemsOnPage(): int
    {
        if (empty($this->_itemsOnPage)) {
            return static::DEFAULT_ITEMS_ON_PAGE_VALUE;
        }

        return (int) $this->_itemsOnPage;
    }

    public function getFields(): array
    {
        if (empty($this->_fields)) {
            $errorMessage = 'SimpleCRUD XML Any Field Is Not Set';
            throw new SimpleCRUDXMLObjectException($errorMessage);
        }

        return $this->_fields;
    }

    public function getSearch(): array
    {
        if (empty($this->_search)) {
            return [];
        }

        return $this->_search;
    }

    public function getAction(?string $actionType = null): ?array
    {
        if (empty($actionType)) {
            return null;
        }

        switch ($actionType) {
            case 'list':
                return $this->_actionList;
                break;

            case 'create':
                return $this->_actionCreate;
                break;

            case 'info':
                return $this->_actionInfo;
                break;

            case 'update':
                return $this->_actionUpdate;
                break;

            case 'remove':
                return $this->_actionRemove;
                break;
            
            default:
                $errorMessage = 'SimpleCRUD XML Unknown Action Type';
                throw new SimpleCRUDXMLObjectException($errorMessage);
                break;
        }
    }

    private function _setValues(): void
    {
        $xmlData = $this->_xmlParser->getFormatedXMLData();

        $this->_setTableAttributes($xmlData);
        $this->_setFields($xmlData);
        $this->_setSearch($xmlData);
        $this->_setActions($xmlData);
    }

    private function _setTableAttributes(array $xmlData): void
    {
        $this->_tableName    = $xmlData['tableAttributes']['name'];
        $this->_primaryField = $xmlData['tableAttributes']['primary_field'];
        $this->_itemsOnPage  = $xmlData['tableAttributes']['items_on_page'];
    }

    private function _setFields(array $xmlData): void
    {
        if (array_key_exists('fields', $xmlData)) {
            $this->_fields = $xmlData['fields'];
        }
    }

    private function _setSearch(array $xmlData): void
    {
        if (array_key_exists('search', $xmlData)) {
            $this->_search = $xmlData['search'];
        }
    }

    private function _setActions(array $xmlData): void
    {
        $actions = $xmlData['actions'];

        $this->_setActionList($xmlData);

        $this->_setActionCreate($actions);
        $this->_setActionInfo($actions);
        $this->_setActionUpdate($actions);
        $this->_setActionRemove($actions);
    }

    private function _setActionList(array $xmlData): void
    {
        $this->_actionList = [
            'title' => $xmlData['tableAttributes']['caption']
        ];
    }

    private function _setActionCreate(array $actions): void
    {
        if (array_key_exists('create', $actions)) {
            $this->_actionCreate = $actions['create'];
        }
    }

    private function _setActionInfo(array $actions): void
    {
        if (array_key_exists('info', $actions)) {
            $this->_actionInfo = $actions['info'];
        }

        if (
            !empty($this->_actionInfo) &&
            !empty($this->_actionInfo['handlers'])
        ) {
            $errorMessage = 'SimpleCRUD Action "Info" '.
                            'Does Not Support Handlers';
            throw new SimpleCRUDXMLObjectException($errorMessage);
        }

        if (
            !empty($this->_actionInfo) &&
            array_key_exists('handlers', $this->_actionInfo)
        ) {
            unset($this->actionInf['handlers']);
        }
    }

    private function _setActionUpdate(array $actions): void
    {
        if (array_key_exists('update', $actions)) {
            $this->_actionUpdate = $actions['update'];
        }
    }

    private function _setActionRemove(array $actions): void
    {
        if (array_key_exists('remove', $actions)) {
            $this->_actionRemove = $actions['remove'];
        }
    }
}
