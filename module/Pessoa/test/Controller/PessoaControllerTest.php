<?php

namespace PessoaTest\Controller;

use Pessoa\Model\Pessoa;
use Prophecy\Argument;
use Pessoa\Model\PessoaTable;
use Zend\ServiceManager\ServiceManager;
use Pessoa\Controller\PessoaController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class PessoaControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $pessoaTable;
    
    protected function mockPessoaTable()
    {
        $this->pessoaTable = $this->prophesize(PessoaTable::class);
        return $this->pessoaTable;
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);
        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(PessoaTable::class, $this->mockPessoaTable()->reveal());
        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    public function setUp(): void
    {
        /**
         * the module configuration should still be applicable for tests
         * you can override configuration here with test case specific values
         * such as view templates, path stacks, module_listener_options
         * etc.
         */
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            // grabbing the full application configuration
           include __DIR__ . '/../../../../config/application.config.php',
           $configOverrides
        ));

        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }
    
    public function testIndexActionCanBeAcessed()
    {
        $this->dispatch('/pessoa');
        $this->assertModuleName('Pessoa');
        $this->assertControllerName(PessoaController::class);
        $this->assertControllerClass('PessoaController');
        $this->assertMatchedRouteName('pessoa');
    }
    
    public function testAddActionRedirectsAfterValidPost()
    {
        $this->pessoaTable
            ->salvarPessoa(Argument::type(Pessoa::class))
            ->shouldBeCalled();

        $postData = [
            'nome' => 'Serhumano',
            'sobrenome' => 'Teste',
            'email' => 'sehumano@teste.com',
            'situacao' => 'idontknow'
        ];

        $this->dispatch('/pessoa/adicionar', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/pessoa');
    }
}