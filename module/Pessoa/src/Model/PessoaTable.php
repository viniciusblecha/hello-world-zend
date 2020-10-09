<?php

namespace Pessoa\Model;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Adapter\Exception\RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PessoaTable 
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResult();
        }

        return $this->tableGateway->select();
    }

    public function fetchPaginatedResult()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the Album entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Pessoa());
    
        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $select,
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );
    
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getPessoa($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf('Não foi encontrado o id %d', $id));
        }
        return $row;
    }

    public function salvarPessoa(Pessoa $pessoa)
    {
        $data = [
            'nome' => $pessoa->getNome(),
            'sobrenome' => $pessoa->getSobrenome(),
            'email' => $pessoa->getEmail(),
            'situacao' => $pessoa->getSituacao(),
        ];

        $id = (int) $pessoa->getId();
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }
        
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deletarPessoa($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
