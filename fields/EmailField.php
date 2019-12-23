<?php
namespace Plugins\SimpleCRUD\Fields;

class EmailField extends TextField
{
    const FIELD_TYPE = 'email';

    const INPUT_MASK = '/^(.*?)@(.*?)\.(.*?)$/su';

    public function getHTML(): string
    {
        // To-Do

        return '';
    }

    public function getHTMLForm(): string
    {
        // To-Do

        return '';
    }
}
