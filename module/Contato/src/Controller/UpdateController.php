<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contato\Controller;

use Cliente\Service\ClienteService;
use Contato\Form\ContatoForm;
use Contato\Model\Contato;
use Contato\Service\ContatoService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UpdateController extends AbstractActionController
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
        $id = $this->params()->fromRoute('id');
        $contato = $this->contatoService->find($id);
        $clienteId = $this->params()->fromRoute('clienteId');
        $cliente = $this->clienteService->find($clienteId);
        if (!$contato || !$cliente || $contato->id_cliente != $clienteId) {
            return $this->redirect()->toRoute('contatos');
        }
        $form = new ContatoForm();
        $form->setData($contato->toArray());
        $form->get('submit')->setValue('Atualizar');
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setVariable('id', $id);
        if (!$cliente) {
            return $this->redirect()->toRoute('clientes');
        }
        $viewModel->setVariable('cliente', $cliente);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $contato = new Contato();
                    $contato->exchangeArray($form->getData());
                    $this->contatoService->update(+$id, $contato);
                } catch (\Exception $e) {
                    return $viewModel
                        ->setVariable('error', $e->getMessage())
                        ->setVariable('form', $form);
                }

                return $this->redirect()->toRoute('contatos', ['clienteId' => $clienteId]);
            }
        }
        return $viewModel->setVariable('form', $form);
    }
}
