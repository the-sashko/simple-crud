<?php
namespace Plugins\SimpleCRUD\Fields;

class UrlField extends TextField
{
    const FIELD_TYPE = 'url';

    const INPUT_MASK = '/^http(s|)\:\/\/(.*?)\.(.*?)$/su';

    public function getHTML(): string
    {
        // To-Do
    }

    public function getHTMLForm(): string
    {
        // To-Do
    }
}
