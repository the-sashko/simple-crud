<?php

$pluginCrudAutoload = function(string $dir, Closure $autoload): void
{
    foreach (glob($dir.'/*') as $fileItem) {
        if ($fileItem == __FILE__) {
            continue;
        }

        if (is_dir($fileItem)) {
            $autoload($fileItem, $autoload);

            continue;
        }

        if (preg_match('/^(.*?)\.php$/', $fileItem)) {
            include_once $fileItem;
        }
    }
};

$pluginCrudAutoload(__DIR__.'/interfaces', $pluginCrudAutoload);

$pluginCrudAutoload(__DIR__.'/exceptions', $pluginCrudAutoload);

require_once __DIR__.'/fields/AbstractField.php';
require_once __DIR__.'/fields/TextField.php';
require_once __DIR__.'/fields/SelectField.php';

$pluginCrudAutoload(__DIR__.'/fields', $pluginCrudAutoload);

require_once __DIR__.'/store/crud.store.credentials.php';
require_once __DIR__.'/store/crud.store.php';

require_once __DIR__.'/search/SimpleCRUDSearchField.php';
require_once __DIR__.'/search/SimpleCRUDSearch.php';

require_once __DIR__.'/actions/AbstractAction.php';
require_once __DIR__.'/actions/CreateAction.php';
require_once __DIR__.'/actions/ListAction.php';
require_once __DIR__.'/actions/InfoAction.php';
require_once __DIR__.'/actions/UpdateAction.php';
require_once __DIR__.'/actions/RemoveAction.php';

require_once __DIR__.'/tables/CoreTable.php';
require_once __DIR__.'/tables/ProxyTable.php';
require_once __DIR__.'/tables/ForeignTable.php';
require_once __DIR__.'/tables/BaseTable.php';

require_once __DIR__.'/xml/simple.crud.xml.class.php';
require_once __DIR__.'/xml/parser.xml.class.php';

require_once __DIR__.'/simple.crud.config.php';
require_once __DIR__.'/simple.crud.content.php';
require_once __DIR__.'/simple.crud.plugin.php';

unset($autoload);
