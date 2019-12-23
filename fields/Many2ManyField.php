<?php
namespace Plugins\SimpleCRUD\Fields;

class Many2ManyField extends SelectField
{
    const FIELD_TYPE = 'many2many';

    public $foreignTable = null;

    public $proxyTable = null;

    public function getHTML(): string
    {
        // To-Do
    }

    public function getHTMLForm(): string
    {
        // To-Do
    }
}
