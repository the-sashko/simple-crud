<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDEntity
{
    public function getFields(): ?array;

    public function executeActionList(ISimpleCRUDContent &$content): void;

    public function executeActionCreate(ISimpleCRUDContent &$content): void;

    public function executeActionUpdate(ISimpleCRUDContent &$content): void;

    public function executeActionRemove(ISimpleCRUDContent &$content): void;

    public function getPrimaryKey(): ?string;

    public function getItemsOnPage(): int;

    public function getName(): ?string;

    public function getAlias(): ?string;

    public function getStore(): ISimpleCRUDStore;

    public function getListAction(): ?ListAction;

    public function getCreateAction(): ?CreateAction;

    public function getUpdateAction(): ?UpdateAction;

    public function getRemoveAction(): ?RemoveAction;
}
