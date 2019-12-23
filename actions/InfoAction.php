<?php
namespace Plugins\SimpleCRUD\Actions;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDInfoActionException;

class InfoAction extends AbstractAction
{
    public function execute(): void
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDInfoActionException($errorMessage);
    }

    private function _getHandlers(): ?array
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDInfoActionException($errorMessage);
    }

    public function executeHandlers(): bool
    {
        $errorMessage = 'SimpleCRUD Method Is Not Implemented';
        throw new SimpleCRUDInfoActionException($errorMessage);
    }
}
