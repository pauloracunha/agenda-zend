<?php

namespace Cliente\Service;

use Cliente\Model\Cliente;
use Zend\Db\Adapter\Adapter;

use function PHPSTORM_META\type;

class ClienteService
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function fetchAll()
    {
        $sql    = 'SELECT * FROM cliente';
        $result = $this->dbAdapter->query($sql, Adapter::QUERY_MODE_EXECUTE);

        /** @var Cliente[] $clientes */
        $clientes = [];
        foreach ($result as $row) {
            $clientes[] = (new Cliente())->exchangeArray((array)$row);
        }

        return $clientes;
    }

    public function find(int $id): Cliente
    {
        $sql = 'SELECT * FROM cliente WHERE id = :id';
        $result = $this->dbAdapter->query($sql, ['id' => $id]);

        $row = $result->current();
        if (!$row) {
            throw new \Exception('Cliente não encontrado');
        }

        return (new Cliente())->exchangeArray((array)$row);
    }

    public function save(Cliente $cliente)
    {
        $cnpjExists = $this->dbAdapter->query('SELECT id FROM cliente WHERE cnpj = :cnpj', ['cnpj' => $cliente->cnpj]);
        if ($cnpjExists->count() > 0) {
            throw new \Exception('CNPJ já cadastrado');
        }
        $data = [
            'nome' => $cliente->nome,
            'cnpj' => preg_replace('/[^0-9]/', '', $cliente->cnpj),
            'endereco' => $cliente->endereco,
            'status' => $cliente->status,
        ];

        $this->dbAdapter->query('INSERT INTO cliente (nome, cnpj, endereco, status) VALUES (:nome, :cnpj, :endereco, :status)', $data);
    }

    public function update(int $id, Cliente $cliente) {
        $cnpjExists = $this->dbAdapter->query('SELECT id FROM cliente WHERE id <> :id AND cnpj = :cnpj', ['id' => $id, 'cnpj' => $cliente->cnpj]);
        if ($cnpjExists->count() > 0) {
            throw new \Exception('CNPJ já cadastrado');
        }

        $data = [
            'id' => $id,
            'nome' => $cliente->nome,
            'cnpj' => preg_replace('/[^0-9]/', '', $cliente->cnpj),
            'endereco' => $cliente->endereco,
            'status' => $cliente->status ? 1 : 0,
        ];

        $sql = 'UPDATE cliente SET nome = :nome, cnpj = :cnpj, endereco = :endereco, status = :status WHERE id = :id';

        $statement = $this->dbAdapter->createStatement($sql);
        $statement->execute($data);
    }

    public function delete(int $id)
    {
        $this->dbAdapter->query('DELETE FROM cliente WHERE id = :id', ['id' => $id]);
    }
}
