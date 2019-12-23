<?php
namespace Plugins\SimpleCRUD\Store\Providers\SQL;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDEntity;
use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDField;
use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearch;
use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearchField;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDMySQLStoreProviderException;

class MySQLStoreProvider extends AbstractSQLStoreProvider
{
    const DB_TYPE = 'mysql';

    const DEFAULT_SEARCH_NODE_CONDITION = 'and';

    const DEFAULT_SEARCH_FIELD_CONDITION = '=';

    private $_fields = null;

    private $_tableAlias = null;

    public function prepareSearch(?ISimpleCRUDEntity $entity = null): string
    {
        $tableAlias = $entity->getAlias();

        if (empty($tableAlias)) {
            $tableAlias = $entity->getName();
        }

        if (empty($tableAlias)) {
            $errorMessage = 'SimpleCRUD Select Table Name Is Not Set';
            throw new SimpleCRUDMySQLStoreProviderException($errorMessage);
        }

        $this->_tableAlias = $tableAlias;

        $search = $entity->getSearch();
        $fields = $entity->getFields();

        if (empty($search)) {
            return 'true';
        }

        if (empty($fields)) {
            return 'false';
        }

        $this->_fields = $fields;

        $sqlSearch = $this->_prepareSearchNode($search);

        if (empty($sqlSearch)) {
            return 'true';
        }

        return $sqlSearch;
    }

    public function prepareSelectFields(
        ?ISimpleCRUDEntity $entity = null
    ): string
    {
        $sqlSelectFields = null;

        $tableAlias = $entity->getAlias();

        if (empty($tableAlias)) {
            $tableAlias = $entity->getName();
        }

        if (empty($tableAlias)) {
            $errorMessage = 'SimpleCRUD Select Table Name Is Not Set';
            throw new SimpleCRUDMySQLStoreProviderException($errorMessage);
        }

        $this->_tableAlias = $tableAlias;

        $fields = (array) $entity->getFields();

        foreach ($fields as $field) {
            $this->_prepareSelectField($field, $sqlSelectFields);
        }

        if (empty($sqlSelectFields)) {
            $errorMessage = 'SimpleCRUD Select Fields Are Not Set';
            throw new SimpleCRUDMySQLStoreProviderException($errorMessage);
        }

        return implode(', ', $sqlSelectFields);
    }

    public function _prepareSelectField(
        ?ISimpleCRUDField  $field           = null,
        ?array            &$sqlSelectFields = null
    ):  bool
    {

        if (empty($field)) {
            return false;
        }

        if (empty($sqlSelectFields)) {
            $sqlSelectFields = [];
        }

        if ($field->isHideOnList()) {
            return false;
        }

        if ($field->getType() == 'many2many') {
            return false;
        }

        $fileName = $field->getName();

        if (empty($fileName)) {
            return false;
        }

        $sqlSelectFields[] = $this->_tableAlias.'.`'.
                             $fileName.'` AS '.$fileName;

        return true;
    }

    private function _prepareSearchNode(
        ?ISimpleCRUDSearch $searchNode = null
    ):  ?string
    {
        if (empty($searchNode)) {
            return null;
        }

        $sqlSearch = [];

        $condition = $searchNode->getCondition();
        $nodes     = $searchNode->getNodes();
        $fields    = $searchNode->getFields();

        $nodes  = empty($nodes) ? [] : $nodes;
        $fields = empty($fields) ? [] : $fields;

        if (empty($condition)) {
            $condition = static::DEFAULT_SEARCH_NODE_CONDITION;
        }

        $condition = ' '.$condition.' ';

        $fields = $this->_prepareSearchFields($fields);
        $nodes  = $this->_prepareSearchNodes($nodes);

        $fields = implode($condition, $fields);
        $nodes = implode($condition, $nodes);

        if (!empty($fields)) {
            $sqlSearch[] = $fields;
        }

        if (!empty($nodes)) {
            $sqlSearch[] = $nodes;
        }

        if (empty($sqlSearch)) {
            return null;
        }

        $sqlSearch = implode($condition, $sqlSearch);

        return '('.$sqlSearch.')';
    }

    private function _prepareSearchField(
        ?ISimpleCRUDSearchField $searchField = null
    ):  ?string
    {
        if (empty($searchField)) {
            return null;
        }

        $searchFieldCondition = $searchField->getCondition();
        $searchFieldName      = $searchField->getField();
        $searchFieldValue     = $searchField->getValue();

        if (empty($searchFieldCondition)) {
            $searchFieldCondition = static::DEFAULT_SEARCH_FIELD_CONDITION;
        }

        if (empty($searchFieldName)) {
            return null;
        }

        $searchFieldValue = $this->_prepareSearchFieldValue(
            $searchFieldName,
            $searchFieldValue
        );

        return $this->_tableAlias.'.`'.$searchFieldName.'` '.
               $searchFieldCondition.' '.$searchFieldValue;
    }

    private function _prepareSearchFieldValue(
        ?string $searchFieldName  = null,
        ?string $searchFieldValue = null 
    ):  ?string
    {
        if (empty($searchFieldName)) {
            return null;
        }

        if (!array_key_exists($searchFieldName, $this->_fields)) {
            $errorMessage = 'SimpleCRUD Field "'.$searchFieldName.
                            '" Can Not Be Used In Search';
            throw new SimpleCRUDMySQLStoreProviderException($errorMessage);
        }

        $field = $this->_fields[$searchFieldName];

        $fieldType = $field->getType();

        switch ($fieldType) {
            case 'text':
                return "'{$searchFieldValue}'";
                break;

            case 'email':
                return "'{$searchFieldValue}'";
                break;

            case 'url':
                return "'{$searchFieldValue}'";
                break;

            case 'number':
                if (empty($searchFieldValue)) {
                    return '0';
                }
                return $searchFieldValue;
                break;

            case 'datetime':
                return "'{$searchFieldValue}'";
                break;

            default:
                $errorMessage = 'SimpleCRUD Field With Type "'.$fieldType.
                                '" Can Not Be Used In Search';
                throw new SimpleCRUDMySQLStoreProviderException($errorMessage);
                break;
        }
    }

    private function _prepareSearchNodes(
        ?array $searchNodes = null,
        ?string $sqlSearch  = null
    ): array
    {
        if (empty($searchNodes)) {
            return [];
        }

        $sqlSearchNodes = [];
        foreach ($searchNodes as $searchNode) {
            if (empty($searchNode)) {
                continue;
            }

            $sqlSearchNodes[] = $this->_prepareSearchNode(
                $searchNode,
                $sqlSearch
            );
        }

        return $sqlSearchNodes;
    }

    private function _prepareSearchFields(?array $searchFields = null): array
    {
        if (empty($searchFields)) {
            return [];
        }

        $sqlSearchFields = [];
        foreach ($searchFields as $searchField) {
            if (empty($searchField)) {
                continue;
            }

            $sqlSearchField = $this->_prepareSearchField($searchField);

            if (empty($sqlSearchField)) {
                continue;
            }

            $sqlSearchFields[] = $sqlSearchField;
        }

        return $sqlSearchFields;
    }

    /*public function create(?array $search = null): bool
    {
        //To-do

        return false;
    }

    public function read(?array $search = null): ?array
    {
        //To-do

        return null;
    }

    public function update(?array $values = null, ?array $search = null): bool
    {
        //To-do

        return false;
    }

    public function delete(?string $search = null): bool
    {
        //To-do

        return false;
    }

    public function count(?string $search = null): int
    {
        //To-do

        return 0;
    }

    public function isExists(?string $search = null): bool
    {
        //To-do

        return false;
    }*/
}
