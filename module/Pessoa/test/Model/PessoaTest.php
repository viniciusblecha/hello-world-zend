<?php

namespace PessoaTest\Model;

use Pessoa\Model\Pessoa;
use PHPUnit\Framework\TestCase;

class PessoaTest extends TestCase
{
    public function testInitialPessoaValuesAreNull()
    {
        $pessoa = new Pessoa();

        $this->assertNull($pessoa->getId(), '"id" should be null by default');
        $this->assertNull($pessoa->getNome(), '"nome" should be null by default');
        $this->assertNull($pessoa->getSobrenome(), '"sobrenome" should be null by default');
        $this->assertNull($pessoa->getEmail(), '"email" should be null by default');
        $this->assertNull($pessoa->getSituacao(), '"situacao" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $pessoa = new Pessoa();
        $data = [
            'id' => 5,
            'nome' => 'vinicius',
            'sobrenome' => 'blecha',
            'email' => 'vb@gmail.com',
            'situacao' => 'inampdsida'
        ];

        $pessoa->exchangeArray($data);

        $this->assertSame(
            $data['id'],
            $pessoa->getId(),
            '"id" was not set correctly'
        );
        $this->assertSame(
            $data['nome'],
            $pessoa->getNome(),
            '"nome" was not set correctly'
        );
        $this->assertSame(
            $data['sobrenome'],
            $pessoa->getSobrenome(),
            '"sobrenome" was not set correctly'
        );
        $this->assertSame(
            $data['email'],
            $pessoa->getEmail(),
            '"email" was not set correctly'
        );
        $this->assertSame(
            $data['situacao'],
            $pessoa->getSituacao(),
            '"situacao" was not set correctly'
        );
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $pessoa = new Pessoa();
        $data = [
            'id' => 5,
            'nome' => 'vinicius',
            'sobrenome' => 'blecha',
            'email' => 'vb@gmail.com',
            'situacao' => 'inampdsida'
        ];

        $pessoa->exchangeArray($data);
        $copyArray = $pessoa->getArrayCopy();

        $this->assertSame(
            $data['id'],
            $copyArray['id'],
            '"id" was not set correctly'
        );
        $this->assertSame(
            $data['nome'],
            $copyArray['nome'],
            '"nome" was not set correctly'
        );
        $this->assertSame(
            $data['sobrenome'],
            $copyArray['sobrenome'],
            '"sobrenome" was not set correctly'
        );
        $this->assertSame(
            $data['email'],
            $copyArray['email'],
            '"email" was not set correctly'
        );
        $this->assertSame(
            $data['situacao'],
            $copyArray['situacao'],
            '"situacao" was not set correctly'
        );
    }
}