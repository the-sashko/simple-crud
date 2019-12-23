<?php
namespace Plugins\SimpleCRUD\XML;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDXMLParserException;

class SimpleCRUDXMLParser
{
    const FIELD_TYPE_SELECT = 'select';

    const FIELD_TYPE_MANY_TO_MANY = 'many2many';

    private $_data = [];

    public function __construct(?string $xmlFilePath = null)
    {
        if (empty($xmlFilePath)) {
            $errorMessage = 'SimpleCRUD XML File Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $xmlObj = $this->_getXMLDataFromFile($xmlFilePath);

        if (empty($xmlObj)) {
            $errorMessage = 'SimpleCRUD XML File Is Empty';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $this->_loadXML($xmlObj);
    }

    public function getFormatedXMLData(): array
    {
        return $this->_data;
    }

    private function _getXMLDataFromFile(
        ?string $xmlFilePath = null
    ):  ?\SimpleXMLElement
    {
        if (empty($xmlFilePath)) {
            return null;
        }

        if (!file_exists($xmlFilePath)) {
            return null;
        }

        if (!is_file($xmlFilePath)) {
            return null;
        }

        $xmldata = file_get_contents($xmlFilePath);

        return new \SimpleXMLElement($xmldata);
    }

    private function _loadXML(\SimpleXMLElement $xmlObj): void
    {
        $this->_setTableAttributes($xmlObj);
        $this->_setFields($xmlObj);
        $this->_setSearch($xmlObj);
        $this->_setActions($xmlObj);
    }

    private function _setActions(\SimpleXMLElement $xmlObj): bool
    {
        if (!isset($xmlObj->actions)) {
            return false;
        }

        if (!isset($xmlObj->actions->action)) {
            return false;
        }

        foreach ($xmlObj->actions->action as $action) {
            $this->_setAction($action);
        }

        return true;
    }

    private function _setAction(\SimpleXMLElement $xmlAction): bool
    {
        if (!array_key_exists('actions', $this->_data)) {
            $this->_data['actions'] = [];
        }

        $actionType  = $xmlAction->attributes()->{'type'};
        $actionTitle = $xmlAction->attributes()->{'title'};

        if (empty($actionType)) {
            $errorMessage = 'SimpleCRUD XML Action Type Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $actionType  = (string) $actionType;
        $actionTitle = (string) $actionTitle;

        if (
            $actionType != 'create' &&
            $actionType != 'update' &&
            $actionType != 'info' &&
            $actionType != 'remove'
        ) {
            $errorMessage = 'SimpleCRUD XML Action Type Has Invalid Value';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($actionTitle)) {
            $actionTitle = mb_convert_case($actionType, MB_CASE_TITLE);
        }

        $actionHandlers = $this->_getActionHandlers($xmlAction);

        $this->_data['actions'][$actionType] = [
            'title'    => $actionTitle,
            'handlers' => $actionHandlers
        ];

        return true;
    }

    private function _getActionHandlers(\SimpleXMLElement $xmlAction): array
    {
        $handlers = [];

        if (!isset($xmlAction->handler)) {
            return $handlers;
        }

        foreach ($xmlAction->handler as $xmlHandler) {
             $handlers[] = $this->_getActionHandler($xmlHandler);
        }

        return $handlers;
    }

    private function _getActionHandler(\SimpleXMLElement $xmlHandler): array
    {
        $handlerPlugin = $xmlHandler->attributes()->{'plugin'};
        $handlerMethod = $xmlHandler->attributes()->{'method'};

        if (empty($handlerPlugin)) {
            $errorMessage = 'SimpleCRUD XML Handler Plugin Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($handlerMethod)) {
            $errorMessage = 'SimpleCRUD XML Handler Method Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        return [
            'plugin' => (string) $handlerPlugin,
            'method' => (string) $handlerMethod
        ];
    }

    private function _setSearch(\SimpleXMLElement $xmlObj): void
    {
        $this->_data['search'] = [];

        if (isset($xmlObj->search)) {
            $this->_data['search'] = $this->_getFieldSearchGroup(
                $xmlObj->search
            );
        }
    }

    private function _setTableAttributes(\SimpleXMLElement $xmlObj):  void
    {
        $tableName         = $xmlObj->attributes()->{'name'};
        $tablePrimaryField = $xmlObj->attributes()->{'primaryField'};
        $tableCaption      = $xmlObj->attributes()->{'caption'};
        $tableItemsOnPage  = $xmlObj->attributes()->{'itemsOnPage'};

        if (empty($tableName)) {
            $errorMessage = 'SimpleCRUD XML Table Name Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($tablePrimaryField)) {
            $errorMessage = 'SimpleCRUD XML Table Primary Field Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($tableCaption)) {
            $tableCaption = mb_convert_case($tableName, MB_CASE_TITLE);
        }

        if (empty($tableItemsOnPage)) {
            $tableItemsOnPage = 0;
        }

        $tableName        = (string) $tableName;
        $tableCaption     = (string) $tableCaption;
        $tableItemsOnPage = (int)    $tableItemsOnPage;

        $tableItemsOnPage = $tableItemsOnPage > 0 ? $tableItemsOnPage : 0;

        $this->_data['tableAttributes'] = [
            'name'          => $tableName,
            'caption'       => $tableCaption,
            'items_on_page' => $tableItemsOnPage,
            'primary_field' => $tablePrimaryField
        ];
    }

    private function _setFields(\SimpleXMLElement $xmlObj): void
    {
        if (!isset($xmlObj->fields)) {
            $errorMessage = 'SimpleCRUD XML Fields Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        foreach ($xmlObj->fields->field as $field) {
            $this->_setField($field);
        }
    }

    private function _setField(\SimpleXMLElement $field): void
    {
        $isRequired = false;
        $isUnique   = false;
        $hideOnList = false;
        $hideOnForm = false;

        if (!array_key_exists('fields', $this->_data)) {
            $this->_data['fields'] = [];
        }

        $fieldName    = $field->attributes()->{'name'};
        $fieldCaption = $field->attributes()->{'caption'};
        $fieldType    = $field->attributes()->{'type'};

        if (empty($fieldName)) {
            $errorMessage = 'SimpleCRUD XML Field Name Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($fieldType)) {
            $errorMessage = 'SimpleCRUD XML Field Type Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($fieldCaption)) {
            $fieldCaption = mb_convert_case($fieldName, MB_CASE_TITLE);
        }

        if (
            $fieldType == static::FIELD_TYPE_MANY_TO_MANY &&
            !preg_match('/^_(.*?)$/su', $fieldName)
        ) {
            $errorMessage = 'SimpleCRUD XML Many2Many Field '.
                            'First Name Character Is Not "_"';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (isset($field->required)) {
            $isRequired = (bool) $field->required;
        }

        if (isset($field->unique)) {
            $isUnique = (bool) $field->unique;
        }

        if (isset($field->hideOnForm)) {
            $hideOnForm = (bool) $field->hideOnForm;
        }

        if (isset($field->hideOnList)) {
            $hideOnList = (bool) $field->hideOnList;
        }

        $fieldName = (string) $fieldName;
        $this->_data['fields'][$fieldName] = [
            'name'         => (string) $fieldName,
            'caption'      => (string) $fieldCaption,
            'type'         => (string) $fieldType,
            'is_required'  => $isRequired,
            'is_unique'    => $isUnique,
            'hide_on_form' => $hideOnForm,
            'hide_on_list' => $hideOnList
        ];

        if (!empty($field->attributes()->{'default'})) {
            $defaultValue = (string) $field->attributes()->{'default'};
            $this->_data['fields'][$fieldName]['default'] = $defaultValue;
        }

        if ($fieldType == static::FIELD_TYPE_SELECT) {
            $this->_setFieldOptions($field);
            $this->_setFieldForeign($field);
        }

        if ($fieldType == static::FIELD_TYPE_MANY_TO_MANY) {
            $this->_setFieldOptions($field);
            $this->_setFieldProxy($field);
            $this->_setFieldForeign($field);
        }
    }

    private function _setFieldProxy(\SimpleXMLElement $field): void
    {
        if (!isset($field->proxy)) {
            $errorMessage = 'SimpleCRUD XML Many2Many Proxy Table Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $fieldName  = $field->attributes()->{'name'};
        $fieldProxy = $field->proxy;

        $fieldProxyName  = $fieldProxy->attributes()->{'name'};
        $fieldProxyAlias = $fieldProxy->attributes()->{'alias'};

        if (empty($fieldProxyName)) {
            $errorMessage = 'SimpleCRUD XML Proxy Field Name Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($fieldProxyAlias)) {
            $fieldProxyAlias = $fieldProxyName;
        }

        if (!isset($fieldProxy->keyField)) {
            $errorMessage = 'SimpleCRUD XML Proxy Field Key Field Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (!isset($fieldProxy->foreignField)) {
            $errorMessage = 'SimpleCRUD XML Proxy Field Foreign Field '.
                            'Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $fieldProxyKeyField     = $fieldProxy->keyField;
        $fieldProxyForeignField = $fieldProxy->foreignField;

        $this->_data['fields'][(string) $fieldName]['proxy'] = [
            'name'          => (string) $fieldProxyName,
            'alias'         => (string) $fieldProxyAlias,
            'key_field'     => (string) $fieldProxyKeyField,
            'foreign_field' => (string) $fieldProxyForeignField
        ];
    }

    private function _setFieldForeign(\SimpleXMLElement $field): bool
    {
        if (!isset($field->foreign)) {
            return false;
        }

        $fieldForeign = $field->foreign;

        $fieldName = (string) $field->attributes()->{'name'};

        $fieldForeignName  = $fieldForeign->attributes()->{'name'};
        $fieldForeignAlias = $fieldForeign->attributes()->{'alias'};

        if (empty($fieldForeignName)) {
            $errorMessage = 'SimpleCRUD XML Foreign Field Name Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (!isset($fieldForeign->keyField)) {
            $errorMessage = 'SimpleCRUD XML Foreign Field Key Field '.
                            'Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($fieldForeignAlias)) {
            $fieldForeignAlias = $fieldForeignName;
        }

        $fieldForeignKeyField     = $fieldForeign->keyField;
        $fieldForeignCaptionField = $fieldForeign->keyField;

        if (isset($fieldForeign->captionField)) {
            $fieldForeignCaptionField = $fieldForeign->captionField;
        }

        $fieldForeignSearch = [];

        if (isset($fieldForeign->search)) {
            $fieldForeignSearch = $this->_getFieldSearchGroup(
                $fieldForeign->search
            );
        }
        
        $this->_data['fields'][$fieldName]['foreign'] = [
            'name'          => (string) $fieldForeignName,
            'alias'         => (string) $fieldForeignAlias,
            'key_field'     => (string) $fieldForeignKeyField,
            'caption_field' => (string) $fieldForeignCaptionField,
            'search'        => $fieldForeignSearch
        ];

        return true;
    }

    private function _getFieldSearchGroup(
        \SimpleXMLElement $fieldSearch
    ): array
    {
        $fieldSearchPrepared = [
            'condition' => null,
            'group'     => [],
            'values'    => []
        ];

        $searchCondition = $fieldSearch->attributes()->{'condition'};

        if (empty($searchCondition)) {
            throw new SimpleCRUDXMLParserException('SimpleCRUD XML Search Condition Is Not Set');
        }

        $searchCondition = (string) $searchCondition;

        if ($searchCondition != 'or' && $searchCondition != 'and') {
            $errorMessage = 'SimpleCRUD XML Search Condition '.
                            'Has Invalid Format';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $fieldSearchPrepared['condition'] = $searchCondition;

        foreach ($fieldSearch->searchValue as $searchValue) {
            $fieldSearchPrepared['values'][] = $this->_getFieldSearchValue(
                $searchValue
            );
        }

        foreach ($fieldSearch->searchGroup as $searchGroup) {
            $fieldSearchPrepared['group'][] = $this->_getFieldSearchGroup(
                $searchGroup
            );
        }

        return $fieldSearchPrepared;
    }

    private function _getFieldSearchValue(
        \SimpleXMLElement $searchValue
    ):  array
    {
        $fieldSearchValuePrepared = [];

        $searchFieldName = $searchValue->attributes()->{'field'};
        $searchCondition = $searchValue->attributes()->{'condition'};
        $searchValue     = $searchValue->attributes()->{'value'};

        if (empty($searchFieldName)) {
            $errorMessage = 'SimpleCRUD XML Search Field Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($searchCondition)) {
            $errorMessage = 'SimpleCRUD XML Search Condition Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($searchValue)) {
            $errorMessage = 'SimpleCRUD XML Search Value Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        $searchFieldName = (string) $searchFieldName;
        $searchCondition = (string) $searchCondition;
        $searchValue     = (string) $searchValue;

        $searchCondition = str_replace('&lt;', '<', $searchCondition);
        $searchCondition = str_replace('&gt;', '>', $searchCondition);
        $searchCondition = str_replace('&quot;', '"', $searchCondition);

        if (
            $searchCondition != '>' &&
            $searchCondition != '<' &&
            $searchCondition != '>=' &&
            $searchCondition != '<=' &&
            $searchCondition != '!=' &&
            $searchCondition != '='
        ) {
            $errorMessage = 'SimpleCRUD XML Search Condition '.
                            'Has Invalid Format';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        return [
            'field'     => $searchFieldName,
            'condition' => $searchCondition,
            'value'     => $searchValue
        ];
    }

    private function _setFieldOptions(\SimpleXMLElement $field): void
    {
        foreach ($field->option as $option) {
            $this->_setFieldOption($field, $option);
        }
    }

    private function _setFieldOption(
        \SimpleXMLElement $field,
        \SimpleXMLElement $option
    ): void
    {
        $fieldName = (string) $field->attributes()->{'name'};

        if (!array_key_exists('options', $this->_data['fields'][$fieldName])) {
            $this->_data['fields'][$fieldName]['options'] = [];
        }

        $optionName     = $option->attributes()->{'name'};
        $optionValue    = $option->attributes()->{'value'};
        $optionSelected = $field->attributes()->{'selected'};

        if (empty($optionName)) {
            $errorMessage = 'SimpleCRUD XML Field Option Name Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($optionValue)) {
            $errorMessage = 'SimpleCRUD XML Field Option Value Is Not Set';
            throw new SimpleCRUDXMLParserException($errorMessage);
        }

        if (empty($fieldCaption)) {
            $fieldCaption = mb_convert_case($fieldName, MB_CASE_TITLE);
        }

        $optionValue = (string) $optionValue;
        $this->_data['fields'][$fieldName]['options'][$optionValue] = [
            'name'     => (string) $optionName,
            'selected' => (bool)   $optionSelected
        ];
    }

    private function _setTableAttribute(string $attribute, string $value): void
    {
        if (!array_key_exists('tableAttributes', $this->_data)) {
            $this->_data['tableAttributes'] = [];
        }

        $this->_data['tableAttributes'][$attribute] = $value;
    }
}
