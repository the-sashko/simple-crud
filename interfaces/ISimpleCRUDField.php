<?php
namespace Plugins\SimpleCRUD\Interfaces;

interface ISimpleCRUDField
{
    public function getName(): ?string;

    public function setValue(?string $value = null): void;

    public function getValue(): ?string;

    public function getType(): string;

    public function getCaption(): ?string;

    public function isRequired(): bool;

    public function isUnique(): bool;

    public function isHideOnList(): bool;

    public function isHideOnForm(): bool;

    public function getDefaultValue(): ?string;
}
