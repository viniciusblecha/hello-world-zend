<?php

namespace Blog\Controller;

use Blog\Form\PostForm;
use Blog\Model\Post;
use Blog\Model\PostCommandInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WriteController extends AbstractActionController
{
    private $command;
    private $form;

    public function __construct(PostCommandInterface $command, PostForm $form)
    {
        $this->command = $command;
        $this->form = $form;
    }

    public function addAction()
    {
        $request = $this->getRequest();
        $viewModel = new ViewModel(['form' => $this->form]);

        if (! $request->isPost()) {
            return $viewModel;
        }

        $this->form->setData($request->getPost());

        if (! $this->form->isValid()) {
            return $viewModel;
        }

        $post = $this->form->getData();

        try {
            $post = $this->command->insertPost($post);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this->redirect()->toRoute(
            'blog/detail',
            ['id' => $post->getId()]
        );
    }
}
