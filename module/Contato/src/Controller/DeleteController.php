<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contato\Controller;

use Contato\Service\ContatoService;
use Zend\Mvc\Controller\AbstractActionController;

class DeleteController extends AbstractActionController
{
    private $contatoService;

    public function __construct(ContatoService $contatoService)
    {
        $this->contatoService = $contatoService;
    }

    public function indexAction()
    {
        $id = $this->params()->fromRoute('id');
        $clienteId = $this->params()->fromRoute('clienteId');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->contatoService->delete($id);
        }
        return $this->redirect()->toRoute('contatos', ['clienteId' => $clienteId]);
    }
}
