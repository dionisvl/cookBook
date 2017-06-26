<?php
/**
 * Модель для работы с БД в админке
 */

namespace Cookbook\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\SqlSelect;
use Doctrine;


class CookbookTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getCat($id)//отбираем конкретный тип блюд
    {
        $type = (int) $id;
        $rowset = $this->tableGateway->select("type = $type");

        return $rowset;
    }

    public function getCookbook($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d', $id
            ));
        }

        return $row;
    }

    public function findCookbook()
    {
        $rowset = $this->tableGateway->select();

        return $rowset;
        //return $this->tableGateway->select();

    }
}
