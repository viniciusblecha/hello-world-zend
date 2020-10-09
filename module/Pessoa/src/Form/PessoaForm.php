<?php

namespace Pessoa\Form;
use Zend\Form\Form;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Email;
use Zend\Form\Element\Submit;

class PessoaForm extends Form
{
    public function __construct()
    {
        parent::__construct('pessoa', []);
        $this->add(new Hidden('id'));
        $this->add(new Text('nome', ['label' => 'Nome']));
        $this->add(new Text('sobrenome', ['label' => 'Sobrenome']));
        $this->add(new Email('email', ['label' => 'Email']));
        $this->add(new Text('situacao', ['label' => 'Situação']));

        $submit = new Submit('submit');
        $submit->setAttributes(['value' => 'Salvar', 'id' => 'submitbutton']);
        $this->add($submit);
    }
}