<?php
namespace Plugins\SimpleCRUD\Fields;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDField;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDFieldException;

abstract class AbstractField implements ISimpleCRUDField
{
    const FIELD_TYPE = null;

    private $_name = null;

    private $_caption = null;

    private $_value = null;

    private $_defaultValue = null;

    private $_isRequired = false;

    private $_isUnique = false;

    private $_isHideOnList = false;

    private $_isHideOnForm = false;

    abstract public function getHTML(): string;

    abstract public function getHTMLForm(): string;

    public function __construct(?array $fieldData)
    {
        if (empty($fieldData)) {
            $errorMessage = 'Can Not Init SimpleCRUD Field: Data Is Empty';
            throw new SimpleCRUDFieldException($errorMessage);
        }

        $name         = null;
        $caption      = null;
        $value        = null;
        $defaultValue = null;
        $isRequired   = false;
        $isUnique     = false;
        $isHideOnList = false;
        $isHideOnForm = false;

        if (array_key_exists('name', $fieldData)) {
            $name = $fieldData['name'];
        }

        if (empty($name)) {
            $errorMessage = 'Can Not Init SimpleCRUD Field: Name Is Not Set';
            throw new SimpleCRUDFieldException($errorMessage);
        }

        if (array_key_exists('caption', $fieldData)) {
            $caption = $fieldData['caption'];
        }

        if (empty($caption)) {
            $caption = mb_convert_case($name, MB_CASE_TITLE);
        }

        if (array_key_exists('value', $fieldData)) {
            $value = $fieldData['value'];
        }

        if (array_key_exists('default', $fieldData)) {
            $defaultValue = $fieldData['default'];
        }

        if (array_key_exists('is_required', $fieldData)) {
            $isRequired = (bool) $fieldData['is_required'];
        }

        if (array_key_exists('is_unique', $fieldData)) {
            $isUnique = (bool) $fieldData['is_unique'];
        }

        if (array_key_exists('hide_on_list', $fieldData)) {
            $isHideOnList = (bool) $fieldData['hide_on_list'];
        }

        if (array_key_exists('hide_on_form', $fieldData)) {
            $isHideOnForm = (bool) $fieldData['hide_on_form'];
        }

        $this->_setName($name);
        $this->_setCaption($caption);
        $this->_setRequired($isRequired);
        $this->_setUnique($isUnique);
        $this->_setHideOnList($isHideOnList);
        $this->_setHideOnForm($isHideOnForm);
        $this->_setDefaultValue($defaultValue);

        $this->setValue($value);
    }

    private function _setName(?string $name = null): void
    {
        $this->_name = $name;
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    public function setValue(?string $value = null): void
    {
        $this->_value = $value;
    }

    public function getValue(): ?string
    {
        if (empty($this->_value)) {
            $this->getDefaultValue();
        }

        return $this->_value;
    }

    public function getType(): string
    {
        if (empty(static::FIELD_TYPE)) {
            $errorMessage = 'Type Of SimpleCRUD Table Field Is Not Set';
            throw new SimpleCRUDFieldException($errorMessage);
        }

        return static::FIELD_TYPE;
    }

    private function _setCaption(?string $caption = null): void
    {
        $this->_caption = $caption;
    }

    public function getCaption(): ?string
    {
        return $this->_caption;
    }

    private function _setRequired(bool $isRequired = false): void
    {
        $this->_isRequired = $isRequired;
    }

    public function isRequired(): bool
    {
        return $this->_isRequired;
    }

    private function _setUnique(bool $isUnique = false): void
    {
        $this->_isUnique = $isUnique;
    }

    public function isUnique(): bool
    {
        return $this->_isUnique;
    }

    private function _setHideOnList(bool $isHideOnList = false): void
    {
        $this->_isHideOnList = $isHideOnList;
    }

    public function isHideOnList(): bool
    {
        return $this->_isHideOnList;
    }

    private function _setHideOnForm(bool $isHideOnForm = false): void
    {
        $this->_isHideOnForm = $isHideOnForm;
    }

    public function isHideOnForm(): bool
    {
        return $this->_isHideOnForm;
    }

    private function _setDefaultValue(?string $defaultValue = null): void
    {
        $this->_defaultValue = $defaultValue;
    }

    public function getDefaultValue(): ?string
    {
        return $this->_defaultValue;
    }
}
