<?php

namespace Contato\Model;

use Cliente\Model\Cliente;

class Contato
{
    public $id;
    public $id_cliente;
    public $nome;
    public $email;
    public $cpf;
    public $cliente;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->nome = !empty($data['nome']) ? $data['nome'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
        $this->cpf = !empty($data['cpf']) ? $data['cpf'] : null;
        $this->id_cliente = !empty($data['id_cliente']) ? $data['id_cliente'] : null;
        $cliente = new Cliente();
        $this->cliente = !empty($data['cliente']) ? $cliente->exchangeArray($data['cliente']) : null;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'id_cliente' => $this->id_cliente,
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'cliente' => !empty($this->cliente) ? $this->cliente->toArray() : null,
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function cliente()
    {
        return $this->cliente;
    }
}
