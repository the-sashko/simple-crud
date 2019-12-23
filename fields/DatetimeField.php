<?php
namespace Plugins\SimpleCRUD\Fields;

class DatetimeField extends TextField
{
    const FIELD_TIPE = 'datetime';

    const INPUT_MASK = '/^\d\d\d\d\-\d\d\-\d\d \d\d\:\d\d\:\d\d$/su';

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
