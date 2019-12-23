<?php
namespace Plugins\SimpleCRUD\Search;

use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearchField;

class SimpleCRUDSearchField implements ISimpleCRUDSearchField
{
    const CONDITION_VALUE_DEFAULT = '=';

    private $_condition = null;

    private $_field = null;

    private $_value = null;

    public function __construct(?array $search = null)
    {
        $search = empty($search) ? [] : $search;

        $this->_setCondition($search);
        $this->_setField($search);
        $this->_setValue($search);
    }

    public function getCondition(): ?string
    {
        return $this->_condition;
    }

    public function getField(): ?string
    {
        return $this->_field;
    }

    public function getValue(): ?string
    {
        return $this->_value;
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

        if (empty($condition)) {
            $this->_condition = static::CONDITION_VALUE_DEFAULT;

            return false;
        }

        $this->_condition = $condition;

        return true;
    }

    private function _setField(?array $search = null): bool
    {
        if (empty($search)) {
            return false;
        }

        if (!array_key_exists('field', $search)) {
            return false;
        }

        $field = $search['field'];

        if (empty($field)) {
            return false;
        }

        $this->_field = $field;

        return true;
    }

    private function _setValue(?array $search = null): bool
    {
        if (empty($search)) {
            return false;
        }

        if (!array_key_exists('value', $search)) {
            return false;
        }

        $value = $search['value'];

        if (empty($value)) {
            return false;
        }

        $this->_value = $value;

        return true;
    }
}
