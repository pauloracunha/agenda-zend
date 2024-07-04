<?php

namespace Contato\Service;

use Cliente\Service\ClienteService;
use Contato\Model\Contato;
use Zend\Db\Adapter\Adapter;

class ContatoService
{
    private $dbAdapter;
    private $clienteService;

    public function __construct(Adapter $dbAdapter, ClienteService $clienteService)
    {
        $this->dbAdapter = $dbAdapter;
        $this->clienteService = $clienteService;
    }

    public function fetchAll($clienteId)
    {
        // TODO: O inner join não é tão necessário aqui, visto que só iremos buscar os contatos de um único cliente
        $sql    = "SELECT 
            contato.id as contato_id,
            contato.nome as contato_nome,
            contato.email as contato_email,
            contato.cpf as contato_cpf,
            contato.id_cliente as contato_id_cliente,
            cliente.id as cliente_id,
            cliente.nome as cliente_nome,
            cliente.cnpj as cliente_cnpj,
            cliente.endereco as cliente_endereco,
            cliente.status as cliente_status
            FROM contato INNER JOIN cliente ON contato.id_cliente = cliente.id WHERE contato.id_cliente = :id_cliente
        ";
        $result = $this->dbAdapter->query($sql, ['id_cliente' => $clienteId]);

        /** @var Contato[] $contatos */
        $contatos = [];
        foreach ($result as $row) {
            $contato = (new Contato())->exchangeArray($this->rawResultToContato((array)$row));
            $contatos[] = $contato;
        }

        return $contatos;
    }

    public function find(int $id): Contato
    {
        // TODO: O inner join não é tão necessário aqui, visto que só iremos buscar um único contato
        $sql    = "SELECT 
            contato.id as contato_id,
            contato.nome as contato_nome,
            contato.email as contato_email,
            contato.cpf as contato_cpf,
            contato.id_cliente as contato_id_cliente,
            cliente.id as cliente_id,
            cliente.nome as cliente_nome,
            cliente.cnpj as cliente_cnpj,
            cliente.endereco as cliente_endereco,
            cliente.status as cliente_status
            FROM contato INNER JOIN cliente ON contato.id_cliente = cliente.id WHERE contato.id = :id";
        $result = $this->dbAdapter->query($sql, ['id' => $id]);

        $row = $result->current();
        if (!$row) {
            throw new \Exception('Contato não encontrado');
        }

        return (new Contato())->exchangeArray($this->rawResultToContato((array)$row));
    }

    public function save(Contato $contato)
    {
        $clienteExists = $this->clienteService->find($contato->id_cliente);
        if (!$clienteExists) {
            throw new \Exception('Cliente não encontrado');
        }
        $cpfOrEmailExists = $this->dbAdapter->query(
            'SELECT id FROM contato WHERE (cpf = :cpf OR email = :email) AND id_cliente = :id_cliente',
            ['cpf' => $contato->cpf, 'email' => $contato->email, 'id_cliente' => $contato->id_cliente]
        );
        if ($cpfOrEmailExists->count() > 0) {
            throw new \Exception('CPF ou Email já cadastrado nos contatos deste cliente');
        }
        $data = [
            'nome' => $contato->nome,
            'email' => $contato->email,
            'cpf' => $contato->cpf,
            'id_cliente' => $contato->id_cliente,
        ];

        $this->dbAdapter->query('INSERT INTO contato (nome, email, cpf, id_cliente) VALUES (:nome, :email, :cpf, :id_cliente)', $data);
    }

    public function update(int $id, Contato $contato) {
        $cpfOrEmailExists = $this->dbAdapter->query(
            'SELECT id FROM contato WHERE id <> :id AND (cpf = :cpf OR email = :email) AND id_cliente = :id_cliente',
            ['id' => $id, 'cpf' => $contato->cpf, 'email' => $contato->email, 'id_cliente' => $contato->id_cliente]
        );

        if ($cpfOrEmailExists->count() > 0) {
            throw new \Exception('CPF ou Email já cadastrado para outro contato deste cliente');
        }

        $data = [
            'id' => $id,
            'nome' => $contato->nome,
            'email' => $contato->email,
            'cpf' => $contato->cpf,
        ];

        $sql = 'UPDATE contato SET nome = :nome, email = :email, cpf = :cpf WHERE id = :id';

        $statement = $this->dbAdapter->createStatement($sql);
        $statement->execute($data);
    }

    public function delete(int $id)
    {
        $this->dbAdapter->query('DELETE FROM contato WHERE id = :id', ['id' => $id]);
    }

    private function rawResultToContato($result)
    {
        return [
            'id' => $result['contato_id'],
            'nome' => $result['contato_nome'],
            'email' => $result['contato_email'],
            'cpf' => $result['contato_cpf'],
            'id_cliente' => $result['contato_id_cliente'],
            'cliente' => [
                'id' => $result['cliente_id'],
                'nome' => $result['cliente_nome'],
                'cnpj' => $result['cliente_cnpj'],
                'endereco' => $result['cliente_endereco'],
                'status' => $result['cliente_status'],
            ],
        ];
    }
}
