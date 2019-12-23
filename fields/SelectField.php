<?php
namespace Plugins\SimpleCRUD\Fields;

class SelectField extends AbstractField
{
    const FIELD_TYPE = 'select';

    const VIEW_TYPE_SELECT   = 'select';
    const VIEW_TYPE_CHECKBOX = 'checkbox';

    public $options = [];

    public $foreignTable = null;

    public $viewType = null;

    public function getHTML(): string
    {
        // To-Do
    }

    public function getHTMLForm(): string
    {
        // To-Do
    }
}
