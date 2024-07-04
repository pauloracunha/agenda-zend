<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contato\Controller;

use Cliente\Service\ClienteService;
use Contato\Service\ContatoService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $contatoService;

    private $clienteService;

    public function __construct(ContatoService $contatoService, ClienteService $clienteService)
    {
        $this->contatoService = $contatoService;
        $this->clienteService = $clienteService;
    }

    public function indexAction()
    {
        $clienteId = $this->params()->fromRoute('clienteId');
        $cliente = $this->clienteService->find($clienteId);
        if (!$cliente) {
            return $this->redirect()->toRoute('clientes');
        }
        $viewModel = new ViewModel();
        $viewModel->setVariable('cliente', $cliente);
        $contatos = $this->contatoService->fetchAll($clienteId);

        return $viewModel->setVariable('contatos', $contatos);
    }
}
