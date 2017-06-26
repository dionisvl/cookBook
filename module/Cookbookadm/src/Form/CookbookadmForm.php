<?php
/**
 *
 */
namespace Cookbookadm\Form;

use Zend\Form\Form;
/**
 * Эта форма используется для формирования полей редактирования записей.
 */
class CookbookadmForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('recepts');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'sostav',
            'type' => 'text',
            'options' => [
                'label' => 'Sostav',
            ],
        ]);
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}