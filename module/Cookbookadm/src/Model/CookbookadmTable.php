<?php
/**
 * Модель для работы с БД в админке
 */

namespace Cookbookadm\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class CookbookadmTable
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

    public function getCookbookadm($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveCookbookadm(Cookbookadm $cookbookadm)
    {
        $data = [
            'name' => $cookbookadm->name,
            'sostav'  => $cookbookadm->sostav,
        ];

        $id = (int) $cookbookadm->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getCookbookadm($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update cookbookadm with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteCookbookadm($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
