<?php
namespace Plugins\SimpleCRUD\Fields;

class TextField extends AbstractField
{
    const FIELD_TYPE = 'text';

    const INPUT_MASK = '/^(.*?)$/su';

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

    private function _isInputHasCorrectFormat(): bool
    {
        // To-Do

        return false;
    }
}
