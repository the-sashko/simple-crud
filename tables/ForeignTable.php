<?php
namespace Plugins\SimpleCRUD\Tables;

class ForeignTable extends CoreTable
{
    public $keyField = null;

    public $captionField = null;

    private function _setKeyField(?string $keyField = NULL): void
    {
        $this->keyField = $keyField;
    }

    public function getKeyField(): ?string
    {
        return $this->keyField;
    }

    private function _setCaptionField(?string $captionField = NULL): void
    {
        $this->captionField = $captionField;
    }

    public function getCaptionField(): ?string
    {
        return $this->captionField;
    }

    private function _setName(string $name): void
    {
        $this->_name = $name;
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    private function _setAlias(?string $alias = null): void
    {
        $this->_alias = empty($alias) ? $this->getName() : $alias;
    }

    public function getAlias(): ?string
    {
        return empty($this->_alias) ? $this->getName() : $this->_alias;
    }
}
