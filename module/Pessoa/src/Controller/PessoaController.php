<?php

namespace Pessoa\Controller;
use Pessoa\Model\Pessoa;
use Pessoa\Form\PessoaForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PessoaController extends AbstractActionController
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        $paginator = $this->table->getAll(true);
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);

        return new ViewModel(['paginator' => $paginator]);
    }

    public function adicionarAction()
    {
        $form = new PessoaForm();
        $form->get('submit')->setValue('Adicionar');
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return new ViewModel(['form' => $form]);
        }
        $pessoa = new Pessoa();
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return new ViewModel(['form' => $form]);
        }
        $pessoa->exchangeArray($form->getData());
        $this->table->salvarPessoa($pessoa);
        return $this->redirect()->toRoute('pessoa');
    }

    public function editarAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'adicionar']);
        }
        try {
            $pessoa = $this->table->getPessoa($id);
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
        }
        $form = new PessoaForm();
        $form->bind($pessoa);
        $form->get('submit')->setAttribute('value', 'Salvar');
        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];
        if (!$request->isPost()) {
            return $viewData;
        }
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            return $viewData;
        }
        $this->table->salvarPessoa($form->getData());
        return $this->redirect()->toRoute('pessoa');
    }

    public function removerAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id === 0) {
            return $this->redirect()->toRoute('pessoa');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Não');
            if ($del == 'Sim') {
                $id = (int) $request->getPost('id');
                $this->table->deletarPessoa($id);
            }
            return $this->redirect()->toRoute('pessoa');
        }
        return ['id' => $id, 'pessoa' => $this->table->getPessoa($id)];
    }
    
    /**
     * /pessoa -> index
     * /pessoa/adicionar -> adicionarAction
     * /pessoa/salvar -> salvarAction
     * /pessoa/editar/1 -> editarAction
     * /pessoa/remover/1 -> removerAction
     * /pessoa/confirmacao -> confirmacaoAction
     */

}
