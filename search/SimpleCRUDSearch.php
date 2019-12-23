<?php
namespace Plugins\SimpleCRUD\Search;

use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearch;

class SimpleCRUDSearch implements ISimpleCRUDSearch
{
    const CONDITION_VALUE_DEFAULT = 'and';

    const CONDITION_VALUE_AND = 'and';

    const CONDITION_VALUE_OR = 'or';

    private $_condition = null;

    private $_nodes = null;

    private $_fields = null;

    public function __construct(?array $search = null)
    {
        $search = empty($search) ? [] : $search;

        $this->_setCondition($search);
        $this->_setNodes($search);
        $this->_setFields($search);
    }

    public function getCondition(): ?string
    {
        return $this->_condition;
    }

    public function getNodes(): ?array
    {
        return $this->_nodes;
    }

    public function getFields(): ?array
    {
        return $this->_fields;
    }

    private function _setCondition(?array $search = null): bool
    {
        if (empty($search)) {
            $this->_condition = static::CONDITION_VALUE_DEFAULT;

            return false;
        }

        if (!array_key_exists('condition', $search)) {
            $this->_condition = static::CONDITION_VALUE_DEFAULT;

            return false;
        }

        $condition = $search['condition'];

        if ($condition != static::CONDITION_VALUE_OR) {
            $this->_condition = static::CONDITION_VALUE_AND;

            return false;
        }

        $this->_condition = static::CONDITION_VALUE_OR;

        return true;
    }

    private function _setFields(?array $search = null): bool
    {
        if (empty($search)) {
            return false;
        }

        if (!array_key_exists('values', $search)) {
            return false;
        }

        $this->_fields = [];

        foreach ($search['values'] as $searchFieldData) {
            if (empty($searchFieldData)) {
                continue;
            }

            $this->_fields[] = new CrudSearchField($searchFieldData);
        }

        if (empty($this->_fields)) {
            $this->_fields = null;
        }

        return true;
    }

    private function _setNodes(?array $search = null): bool
    {
        if (empty($search)) {
            return false;
        }

        if (!array_key_exists('group', $search)) {
            return false;
        }

        $this->_nodes = [];

        foreach ($search['group'] as $searchNodeData) {
            if (empty($searchNodeData)) {
                continue;
            }

            $this->_nodes[] = new CrudSearch($searchNodeData);
        }

        if (empty($this->_nodes)) {
            $this->_nodes = null;
        }

        return true;
    }
}
