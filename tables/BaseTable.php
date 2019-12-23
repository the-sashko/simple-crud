<?php
namespace Plugins\SimpleCRUD\Tables;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDXMLObject;
use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDEntity;
use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDContent;
use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDConfig;

use Plugins\SimpleCRUD\Interfaces\Store\ISimpleCRUDStore;

use Plugins\SimpleCRUD\Interfaces\Search\ISimpleCRUDSearch;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDBaseTableException;

use Plugins\SimpleCRUD\Actions\ListAction;
use Plugins\SimpleCRUD\Actions\CreateAction;
use Plugins\SimpleCRUD\Actions\InfoAction;
use Plugins\SimpleCRUD\Actions\UpdateAction;
use Plugins\SimpleCRUD\Actions\RemoveAction;

use Plugins\SimpleCRUD\Fields\TextField;
use Plugins\SimpleCRUD\Fields\TextAreaField;
use Plugins\SimpleCRUD\Fields\EmailField;
use Plugins\SimpleCRUD\Fields\DatetimeField;
use Plugins\SimpleCRUD\Fields\UrlField;
use Plugins\SimpleCRUD\Fields\PasswordField;
use Plugins\SimpleCRUD\Fields\NumberField;
use Plugins\SimpleCRUD\Fields\SelectField;
use Plugins\SimpleCRUD\Fields\Many2ManyField;
use Plugins\SimpleCRUD\Fields\FileField;
use Plugins\SimpleCRUD\Fields\ImageField;
use Plugins\SimpleCRUD\Fields\CheckboxField;

use Plugins\SimpleCRUD\Search\SimpleCRUDSearch;

use Plugins\SimpleCRUD\Store\SimpleCRUDStore;

class BaseTable extends CoreTable implements ISimpleCRUDEntity
{
    private $_primaryKey = null;

    private $_itemsOnPage = null;

    private $_search = null;

    private $_fields = [];

    private $_actionList = null;

    private $_actionCreate = null;

    private $_actionUpdate = null;

    private $_actionRemove = null;

    private $_store = null;

    private $_content = null;

    public function __construct(
        ?ISimpleCRUDXMLObject $crudXML    = null,
        ?ISimpleCRUDConfig    $crudConfig = null,
        array                 $formData   = []
    )
    {
        $this->_setValuesFromCrudXML($crudXML);
        $this->_setValuesFromFormData($formData);
        $this->_initStore($crudConfig);
    }

    public function getFields(): ?array
    {
        return $this->_fields;
    }

    public function executeActionList(ISimpleCRUDContent &$content): void
    {
        if (empty($this->_actionList)) {
            $errorMessage = 'SimpleCRUD Action "List" '.
                            'Is Not Allow In This Table';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $this->_content = $content;

        $this->_actionList->execute();
    }

    public function executeActionCreate(ISimpleCRUDContent &$content): void
    {
        if (empty($this->_actionCreate)) {
            $errorMessage = 'SimpleCRUD Action "Create" '.
                            'Is Not Allow In This Table';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $this->_content = $content;

        $this->_actionList->executeHandlers();
        $this->_actionCreate->execute();
    }

    public function executeActionUpdate(ISimpleCRUDContent &$content): void
    {
        if (empty($this->_actionUpdate)) {
            $errorMessage = 'SimpleCRUD Action "Update" '.
                            'Is Not Allow In This Table';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $this->_content = $content;

        $this->_actionList->executeHandlers();
        $this->_actionUpdate->execute();
    }

    public function executeActionRemove(ISimpleCRUDContent &$content): void
    {
        if (empty($this->_actionRemove)) {
            $errorMessage = 'SimpleCRUD Action "Remove" '.
                            'Is Not Allow In This Table';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $this->_content = $content;

        $this->_actionList->executeHandlers();
        $this->_actionRemove->execute();
    }

    private function _setValuesFromCrudXML(
        ?ISimpleCRUDXMLObject $crudXML = null
    ):  void
    {
        if (empty($crudXML)) {
            $errorMessage = 'SimpleCRUD XML Object Is Not Set';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $this->_setName($crudXML->getTableName());
        $this->_setAlias($crudXML->getTableName());
        $this->_setPrimaryKey($crudXML->getPrimaryField());
        $this->_setItemsOnPage($crudXML->getItemsOnPage());
        $this->_setFields($crudXML->getFields());
        $this->_setSearch($crudXML->getSearch());

        $this->_setActions($crudXML);
    }

    private function _setActions(ISimpleCRUDXMLObject $crudXML): void
    {
        $this->_setActionList($crudXML->getAction('list'));
        $this->_setActionCreate($crudXML->getAction('create'));
        $this->_setActionInfo($crudXML->getAction('info'));
        $this->_setActionUpdate($crudXML->getAction('update'));
        $this->_setActionRemove($crudXML->getAction('remove'));
    }

    private function _setActionList(?array $actionData = null): bool
    {
        if (empty($actionData)) {
            return false;
        }

        $this->_actionList = new ListAction($actionData, $this);

        return true;
    }


    private function _setActionCreate(?array $actionData = null): bool
    {
        if (empty($actionData)) {
            return false;
        }

        $this->_actionCreate = new CreateAction($actionData, $this);

        return true;
    }


    private function _setActionInfo(?array $actionData = null): bool
    {
        if (empty($actionData)) {
            return false;
        }

        $this->actionInfo = new InfoAction($actionData, $this);

        return true;
    }


    private function _setActionUpdate(?array $actionData = null): bool
    {
        if (empty($actionData)) {
            return false;
        }

        $this->_actionUpdate = new UpdateAction($actionData, $this);

        return true;
    }


    private function _setActionRemove(?array $actionData = null): bool
    {
        if (empty($actionRemove)) {
            return false;
        }

        $this->_actionRemove = new RemoveAction($actionData, $this);

        return true;
    }

    private function _setValuesFromFormData(array $formData = []): void
    {
        //To-Do
    }

    private function _setFields(array $fields = []): void
    {
        foreach ($fields as $field) {
            $this->_setField($field);
        }
    }

    private function _setField(array $field = []): void
    {
        if (!array_key_exists('type', $field)) {
            $errorMessage = 'SimpleCRUD Field Type Is Not Set';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        $fieldType = $field['type'];

        switch ($fieldType) {
            case 'text':
                $field = new TextField($field);
                break;

            case 'textarea':
                $field = new TextAreaField($field);
                break;

            case 'email':
                $field = new EmailField($field);
                break;

            case 'password':
                $field = new PasswordField($field);
                break;

            case 'url':
                $field = new UrlField($field);
                break;

            case 'number':
                $field = new NumberField($field);
                break;

            case 'datetime':
                $field = new DatetimeField($field);
                break;

            case 'file':
                $field = new FileField($field);
                break;

            case 'image':
                $field = new ImageField($field);
                break;

            case 'select':
                $field = new SelectField($field);
                break;

            case 'many2many':
                $field = new Many2ManyField($field);
                break;

            case 'checkbox':
                $field = new CheckboxField($field);
                break;

            default:
                $errorMessage = 'SimpleCRUD Field Type "'.$fieldType.'" '.
                               'Is Not Supported';
                throw new SimpleCRUDBaseTableException($errorMessage);
                break;
        }

        $this->_fields[$field->getName()] = $field;
    }

    private function _setSearch(?array $search = null): void
    {
        $search = empty($search) ? [] : $search;

        $this->_search = new SimpleCRUDSearch($search);
    }

    public function getSearch(): ?ISimpleCRUDSearch
    {
        return $this->_search;
    }

    private function _setPrimaryKey(string $primaryKey): void
    {
        $this->_primaryKey = $primaryKey;
    }

    public function getPrimaryKey(): ?string
    {
        return $this->_primaryKey;
    }

    private function _setItemsOnPage(int $itemsOnPage): void
    {
        $this->_itemsOnPage = $itemsOnPage;
    }

    public function getItemsOnPage(): int
    {
        return $this->_itemsOnPage;
    }

    private function _setName(string $name): void
    {
        $this->_name = $name;
    }

    public function getName(): ?string
    {
        return $this->_name;
    }

    private function _setAlias(?string $alias = null): void
    {
        $this->_alias = empty($alias) ? $this->getName() : $alias;
    }

    public function getAlias(): ?string
    {
        return empty($this->_alias) ? $this->getName() : $this->_alias;
    }

    private function _initStore(?ISimpleCRUDConfig $crudConfig = null): void
    {
        $storeType        = $crudConfig->getStoreType();
        $cacheType        = $crudConfig->getCacheType();
        $storeCredentials = $crudConfig->getStoreCredentials();

        $this->_store = new SimpleCRUDStore(
            $storeType,
            $cacheType,
            $storeCredentials
        );
    }

    public function getStore(): ISimpleCRUDStore
    {
        if (empty($this->_store)) {
            $errorMessage = 'SimpleCRUD Store Is Not Set';
            throw new SimpleCRUDBaseTableException($errorMessage);
        }

        return $this->_store;
    }

    public function getListAction(): ?ListAction
    {
        return $this->_actionList;
    }

    public function getCreateAction(): ?CreateAction
    {
        return $this->_actionCreate;
    }

    public function getUpdateAction(): ?UpdateAction
    {
        return $this->_actionUpdate;
    }

    public function getRemoveAction(): ?RemoveAction
    {
        return $this->_actionRemove;
    }
}
