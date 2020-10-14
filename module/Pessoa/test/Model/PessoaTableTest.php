<?php

namespace PessoaTest\Model;

use Pessoa\Model\PessoaTable;
use Pessoa\Model\Pessoa;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class PessoaTableTest extends TestCase
{
    protected function setUp(): void
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->pessoaTable = new PessoaTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllPessoas()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->pessoaTable->getAll(false));
    }

    public function testCanDeleteAnPessoaById()
    {
        $this->tableGateway->delete(['id' => 5])->shouldBeCalled();
        $this->pessoaTable->deletarPessoa(5);
    }

    public function testSavePessoaWillInsertNewPessoaIfTheDontAlreadyHaveAnId()
    {
        $pessoaData = [
            'nome' => 'vinicius',
            'sobrenome' => 'blecha',
            'email' => 'vb@gmail.com',
            'situacao' => 'inampdsida'
        ];

        $pessoa =  new Pessoa();
        $pessoa->exchangeArray($pessoaData);

        $this->tableGateway->insert($pessoaData)->shouldBeCalled();
        $this->pessoaTable->salvarPessoa($pessoa);
    }

    public function testSavePessoaWillUpdateExistingPessoaIfTheyAlreadyHaveAnId()
    {
        $pessoaData = [
            'id' => 5,
            'nome' => 'vinicius',
            'sobrenome' => 'blecha',
            'email' => 'vb@gmail.com',
            'situacao' => 'inampdsida'
        ];

        $pessoa = new Pessoa();
        $pessoa->exchangeArray($pessoaData);

        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn($pessoa);

        $this->tableGateway
            ->select(['id' => 5])
            ->willReturn($resultSet->reveal());
        $this->tableGateway
            ->update(
                array_filter($pessoaData, function ($key) {
                    return in_array($key, ['nome', 'sobrenome', 'email', 'situacao']);
                }, ARRAY_FILTER_USE_KEY),
                ['id' => 5]
            )->shouldBeCalled();

            $this->pessoaTable->salvarPessoa($pessoa);
    }

    public function testExceptionIsThrownWhenGettingNonExistentPessoa()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn(null);

        $this->tableGateway
            ->select(['id' => 5])
            ->willReturn($resultSet->reveal());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Não foi encontrado o id 5');
        $this->pessoaTable->getPessoa(5);
    }
}