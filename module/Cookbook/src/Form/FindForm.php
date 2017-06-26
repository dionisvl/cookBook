<?php
/**
 *
 */
namespace Cookbook\Form;

use Zend\Form\Form;
/**
 * Эта форма используется для поиска по таблице recepts.
 */
class FindForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('recepts');

        //поле для поиска
        $this->add([
            'name' => 'find',
            'type' => 'text',
            'options' => [
                'label' => 'Find',
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