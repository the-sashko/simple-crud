<?php
namespace Plugins\SimpleCRUD\Actions;

use Plugins\SimpleCRUD\SimpleCRUDContent;
use Plugins\SimpleCRUD\Exceptions\SimpleCRUDListActionException;

class ListAction extends AbstractAction
{
    public function execute(): void
    {
        $store = $this->entity->getStore();

        $rows = $store->read($this->entity);

        if (empty($rowsData)) {
            //
        }

        foreach ($rows as $rowIdx => $row) {
            $this->_setRow($rows[$rowIdx]);
        }

        $templateParams = array(
            'entity' => $this->entity,
            'title'       => $this->getTitle(),
            'rows'        => $rows,
            'titleRow'    => end($rows)
        );

        echo (new SimpleCRUDContent)->renderTemplate('table', $templateParams, true);
        die();
    }

    public function getHandlers(): array
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDListActionException();

        return [];
    }

    public function executeHandlers(): bool
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDListActionException($errorMessage);

        return false;
    }

    private function _setRow(?array &$row = null): void
    {
        foreach ($this->entity->getFields() as $fieldName => $field) {
            if (!array_key_exists($fieldName, $row)) {
                continue;
            }

            $rowValue = $row[$fieldName];
            $row[$fieldName] = clone $field;
            $row[$fieldName]->setValue($rowValue);
        }
    }
}
