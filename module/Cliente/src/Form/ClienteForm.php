<?php

namespace Cliente\Form;

use Zend\Form\Element;
use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Regex;
use Zend\Validator\StringLength;

class ClienteForm extends Form implements InputFilterAwareInterface
{
    protected $inputFilter;

    public function __construct($name = null)
    {
        parent::__construct('cliente');
        $id = new Element('id');
        $id->setAttribute('type', 'hidden');

        $nome = new Element('nome');
        $nome->setLabel('Nome do Cliente');
        $nome->setAttributes([
            'type' => 'text',
            'required' => 'required',
            'class' => 'form-control',
        ]);

        $cnpj = new Element('cnpj');
        $cnpj->setLabel('CNPJ');
        $cnpj->setAttributes([
            'type' => 'text',
            'required' => 'required',
            'class' => 'form-control cnpj',
        ]);

        $endereco = new Element('endereco');
        $endereco->setLabel('Endereço');
        $endereco->setAttributes([
            'type' => 'text',
            'required' => 'required',
            'class' => 'form-control',
        ]);

        $status = new Element\Radio('status');
        $status->setValueOptions([
            1 => 'Ativo',
            0 => 'Inativo',
        ]);
        $status->setAttributes([
            'class' => 'form-check-input',
            'value' => '1',
            'required' => 'required',
        ]);
        $status->setLabelAttributes([
            'class' => 'form-check-label px-2',
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
        $this->add($nome);
        $this->add($cnpj);
        $this->add($endereco);
        $this->add($status);
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
                'name' => 'cnpj',
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
                            'max' => 18,
                        ],
                    ],
                    [
                        'name' => CnpjValidator::class,
                    ]
                ],
            ]);

            $inputFilter->add([
                'name' => 'endereco',
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
                            'max' => 255,
                        ],
                    ],
                ],
            ]);

            $inputFilter->add([
                'name' => 'status',
                'required' => true,
                'filters' => [
                    ['name' => ToInt::class],
                ],
            ]);

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}
