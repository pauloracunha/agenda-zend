<?php

namespace Contato\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\StringLength;

class ContatoForm extends Form implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function __construct($name = null)
    {
        parent::__construct('contato');
        $id = new Element('id');
        $id->setAttribute('type', 'hidden');

        $clienteId = new Element('id_cliente');
        $clienteId->setAttribute('type', 'hidden');

        $nome = new Element('nome');
        $nome->setLabel('Nome do Contato');
        $nome->setAttributes([
            'type' => 'text',
            'required' => 'required',
            'class' => 'form-control',
        ]);

        $email = new Element\Email('email');
        $email->setLabel('Email do contato');
        $email->setAttributes([
            'required' => 'required',
            'class' => 'form-control',
        ]);

        $cpf = new Element('cpf');
        $cpf->setLabel('CPF do contato');
        $cpf->setAttributes([
            'type' => 'text',
            'required' => 'required',
            'class' => 'form-control cpf',
        ]);

        $send = new Element('submit');
        $send->setAttributes([
            'type' => 'submit',
            'value' => 'Cadastrar',
            'id' => 'submitbutton',
            'class' => 'btn btn-primary',
        ]);

        $csrf = new Element\Csrf('security');

        $this->add($id);
        $this->add($clienteId);
        $this->add($nome);
        $this->add($email);
        $this->add($cpf);
        $this->add($send);
        $this->add($csrf);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add([
                'name' => 'nome',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 100,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'cpf',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 14,
                        ],
                    ],
                    [
                        'name' => CpfValidator::class,
                    ]
                ],
            ]);

            $inputFilter->add([
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => EmailAddress::class,
                    ],
                ],
            ]);

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}
