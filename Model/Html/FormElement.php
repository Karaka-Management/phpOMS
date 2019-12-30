<?php

class FormElement
{
    public string $id;
    public string $form;
    public string $name;
    public bool $required;
    public string $defaultValue;
    public string $requiredValue;
    public bool $autosave;

    public function __construct(
        string $id = '',
        string $form = '',
        string $name = '',
        bool $required = false,
        string $defaultValue = '',
        string $requiredValue = '',
        bool $autosave = false
    ) {
        $this->id            = $id;
        $this->form          = $form;
        $this->name          = $name;
        $this->required      = $required;
        $this->defaultValue  = $defaultValue;
        $this->requiredValue = $requiredValue;
        $this->autosave      = $autosave;
    }
}
