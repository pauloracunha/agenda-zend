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

class CreateController extends AbstractActionController
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
        $form = new ContatoForm();
        $form->get('submit')->setValue('Cadastrar');
        $form->get('id_cliente')->setValue($clienteId);
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $cliente = $this->clienteService->find($clienteId);
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
                    $contato->id_cliente = $clienteId;
                    $this->contatoService->save($contato);
                } catch (\Exception $e) {
                    return $viewModel
                        ->setVariable('error', $e->getMessage())
                        ->setVariable('form', $form);
                }

                return $this->redirect()->toRoute('contatos', ['clienteId' => $clienteId]);
            }
            print_r($form->getMessages()); die;
        }
        return $viewModel->setVariable('form', $form);
    }
}
