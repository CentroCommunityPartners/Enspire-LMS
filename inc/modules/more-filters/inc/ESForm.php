<?php

class ESForm
{
    protected $fieldClass;
    protected $fields;
    protected $formClass;

    function __construct($fields)
    {

        $this->fieldClass = [
            'text' => 'EsFieldText',
            'number' => 'EsFieldNumber',
            'email' => 'EsFieldEmail',
            'phone' => 'EsFieldPhone',
            'url' => 'EsFieldUrl',
            'post_title' => 'EsFieldPostTitle',
            'user_phone' => 'EsFieldUserPhone',
            'user_email' => 'EsFieldUserEmail',
            'select' => 'EsFieldSelect',
            'select_grouped' => 'EsFieldSelectGrouped',
            'select_hierarchical' => 'EsFieldSelectHierarchical',
            'checkbox' => 'EsFieldCheckbox',
            'country' => 'EsFieldCountry',
            'editor' => 'EsFieldEditor',
            'heading' => 'EsFieldHeading',
            'button' => 'EsFieldButton'
        ];

        $this->formClass = 'esl-form';
        $this->fields = $fields;
    }

    function set_classes($clasess)
    {
        $this->formClass = $this->formClass . ' ' . $clasess;
    }

    function render()
    {
        echo '<form class="' . $this->formClass . '" method="post">';
        foreach ($this->fields as $item) {
            $class_name = isset($this->fieldClass[$item['field']]) ? $this->fieldClass[$item['field']] : '';
            if ($class_name) {
                $class = new $class_name($item);
                $class->render();
            }
        }
        echo '</form>';
    }
}
