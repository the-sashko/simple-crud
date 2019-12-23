<?php
namespace Plugins\SimpleCRUD;

use Plugins\SimpleCRUD\Interfaces\ISimpleCRUDContent;

class SimpleCRUDContent implements ISimpleCRUDContent
{
    const TYPE_HTML = 'html';

    const TYPE_JSON = 'json';

    const DEFAULT_ERROR_MESSAGE = 'Unknown Error';

    private $_type = null;

    private $_status = false;

    private $_dataJSON = null;

    private $_dataHTML = null;

    public function __construct()
    {
        $this->_type = static::TYPE_JSON;
    }

    public function getType(): string
    {
        return (string) $this->_type;
    }

    public function getStatus(): bool
    {
        return (bool) $this->_status;
    }

    public function isJSON(): bool
    {
        return $this->getType() == static::TYPE_JSON;
    }

    public function isHTML(): bool
    {
        return $this->getType() == static::TYPE_HTML;
    }

    public function getJSON(): string
    {
        $dataJSON = [
            'status' => $this->_status,
            'data'   => $this->_dataJSON
        ];

        return json_encode($dataJSON);
    }

    public function setStatus(bool $status = false): void
    {
        $this->_status = $status;
    }

    public function setError(?string $errorMessage = null): void
    {
        if (empty($errorMessage)) {
            $errorMessage = static::DEFAULT_ERROR_MESSAGE;
        }

        $dataJSON = [
            'error' => $errorMessage
        ];

        $this->setDataJSON($dataJSON);
        $this->setStatus(false);
    }

    public function setDataJSON(array $dataJSON = []): void
    {
        $this->_dataJSON = $dataJSON;
        $this->setTypeJSON();
    }

    public function setDataHTML(?array $dataHTML = null): void
    {
        $this->_dataJSON = (string) $dataHTML;
        $this->setTypeHTML();
    }

    public function getHTML(): string
    {
        return (string) $this->_dataHTML;
    }

    public function setTypeHTML(): void
    {
        $this->_setType(static::TYPE_HTML);
    }

    public function setTypeJSON(): void
    {
        $this->_setType(static::TYPE_JSON);
    }

    public function renderTemplate(
        ?string $templateName     = null,
        ?array  $templateValues   = null,
        bool    $isRenderFullPage = false
    ):  ?string
    {
        require_once(__DIR__.'/tpl/'.$templateName.'.phtml');

        return null;
    }

    private function _setType(string $type): void
    {
        $this->_type = $type;
    }
}
