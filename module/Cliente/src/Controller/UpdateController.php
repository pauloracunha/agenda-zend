<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cliente\Controller;

use Cliente\Form\ClienteForm;
use Cliente\Model\Cliente;
use Cliente\Service\ClienteService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UpdateController extends AbstractActionController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        $cliente = $this->clienteService->find($id);
        $form = new ClienteForm();
        $form->setData($cliente->toArray());
        $form->get('submit')->setValue('Atualizar');
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $viewModel->setVariable('id', $id);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $cliente = new Cliente();
                    $cliente->exchangeArray($form->getData());
                    $this->clienteService->update(+$id, $cliente);
                } catch (\Exception $e) {
                    return $viewModel
                        ->setVariable('error', $e->getMessage())
                        ->setVariable('form', $form);
                }

                return $this->redirect()->toRoute('clientes');
            }
        }
        return $viewModel->setVariable('form', $form);
    }
}
