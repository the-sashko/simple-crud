<?php
namespace Plugins\SimpleCRUD\Fields;

class NumberField extends TextField
{
    const FIELD_TYPE = 'number';

    const INPUT_MASK = '/^([0-9]+)$/su';

    public function getHTML(): string
    {
        // To-Do
    }

    public function getHTMLForm(): string
    {
        // To-Do
    }
}
