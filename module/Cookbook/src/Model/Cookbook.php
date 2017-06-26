<?php
// module/Album/src/Model/Cookbook.php:
namespace Cookbook\Model;

class Cookbook
{
    public $id;
    public $name;
    public $sostav;

//Для работы с классом TableGateway компонента zend-db, нам необходимо реализовать метод exchangeArray();
// этот метод копирует данные из предоставленного массива в свойства нашего объекта.
//Позднее мы добавим входной фильтр, чтобы убедиться, что введенные значения действительны.
    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->sostav  = !empty($data['sostav']) ? $data['sostav'] : null;
    }

    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'name' => $this->name,
            'sostav'  => $this->sostav,
        ];
    }
}