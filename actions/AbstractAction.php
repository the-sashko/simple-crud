<?php
namespace Plugins\SimpleCRUD\Actions;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDEntity;

use Plugins\SimpleCRUD\Exceptions\SimpleCRUDActionException;

abstract class AbstractAction
{
    private $_handlers = null;
    private $_title    = null;

    public $entity = null;

    abstract public function execute();

    public function __construct(
        ?array        $actionData = null,
        ?ICrudEntity &$entity     = null
    )
    {
        $this->entity = $entity;

        if (empty($actionData)) {
            $errorMessage = 'SimpleCRUD Action Data Is Not Set';
            throw new SimpleCRUDActionException($errorMessage);
        }

        if (array_key_exists('title', $actionData)) {
            $this->_setTitle((string) $actionData['title']);
        }

        if (array_key_exists('handlers', $actionData)) {
            $this->_setHandlers((array) $actionData['handlers']);
        }
    }

    private function _getHandlers(): ?array
    {
        return $this->_handlers;
    }

    public function executeHandlers(): bool
    {
        $handlers = $this->_getHandlers();

        if (empty($handlers)) {
            return false;
        }

        foreach ($handlers as $handler) {
            $this->_executeHandler($handler);
        }

        return true;
    }

    private function _executeHandler(?IActionHandler $handler = null): bool
    {
        if (empty($handler)) {
            return false;
        }

        $plugin = $handler->getPluginInstance();

        if (empty($plugin)) {
            return false;
        }

        $method = $handler->getMethodName();

        if (empty($method)) {
            return false;
        }

        if (!method_exists($plugin, $method)) {
            $errorMessage = 'SimpleCRUD Handler Method Is Not Exists';
            throw new SimpleCRUDActionException($errorMessage);
        }

        $plugin->$method($this->_entity);

        return true;
    }

    private function _setHandlers(?array $handlers = null): bool
    {
        if (empty($handlers)) {
            return false;
        }

        $this->_handlers = $handlers;

        return true;
    }

    public function getTitle(): string
    {
        if (empty($this->_title)) {
            $errorMessage = 'SimpleCRUD Method Title Is Not Set';
            throw new SimpleCRUDActionException($errorMessage);
        }

        return (string) $this->_title;
    }

    private function _setTitle(?string $title = null): bool
    {
        if (empty($title)) {
            return false;
        }

        $this->_title = $title;

        return true;
    }
}
