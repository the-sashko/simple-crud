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
            // To-Do
        }

        foreach ($rows as $rowIdx => $row) {
            $this->_setRow($row);
            $rows[$rowIdx] = $row;
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

    private function _getHandlers(): ?array
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDListActionException($errorMessage);
    }

    public function executeHandlers(): bool
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDListActionException($errorMessage);
    }

    private function _setRow(?array &$row = null): void
    {
        foreach ($this->entity->getFields() as $fieldName => $field) {
            if (!array_key_exists($fieldName, $row)) {
                continue;
            }

            $rowValue = isset($row[$fieldName]) ? $row[$fieldName] : null;
            $row[$fieldName] = clone $field;
            $row[$fieldName]->setValue($rowValue);
        }
    }
}
