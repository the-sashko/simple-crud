<?php

namespace Plugins\SimpleCRUD\Exceptions;

class SimpleCRUDConfigException extends \Exception
{
    const CODE_CONFIG_NOT_SET = 1;

    const CODE_EMPTY_CONFIG_FILE = 2;

    const CODE_EMPTY_CONFIG_PARAM = 2;
}
