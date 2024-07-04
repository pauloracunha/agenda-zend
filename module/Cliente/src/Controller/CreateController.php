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

class CreateController extends AbstractActionController
{
    private $clienteService;

    public function __construct(ClienteService $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function indexAction()
    {
        $form = new ClienteForm();
        $form->get('submit')->setValue('Cadastrar');
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $cliente = new Cliente();
                    $cliente->exchangeArray($form->getData());
                    $this->clienteService->save($cliente);
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
