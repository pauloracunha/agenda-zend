<?php

namespace Cliente\Model;

class Cliente
{
    public $id;
    public $nome;
    public $cnpj;
    public $endereco;
    public $status;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->nome = !empty($data['nome']) ? $data['nome'] : null;
        $this->cnpj = !empty($data['cnpj']) ? $data['cnpj'] : null;
        $this->endereco = !empty($data['endereco']) ? $data['endereco'] : null;
        $this->status = !empty($data['status']) ? $data['status'] : 0;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cnpj' => $this->cnpj,
            'endereco' => $this->endereco,
            'status' => $this->status,
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

    public function getCnpj()
    {
        return $this->cnpj;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function getStatus()
    {
        return $this->status ? 'Ativo' : 'Inativo';
    }
}
