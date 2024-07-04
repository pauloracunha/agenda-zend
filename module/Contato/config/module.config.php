<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Contato;

use Cliente\Service\ClienteService;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'contatos' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cliente/:clienteId/contatos',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'clienteId' => '[0-9]+',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'create' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'controller' => Controller\CreateController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'update' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/atualizar[/:id]',
                            'defaults' => [
                                'controller' => Controller\UpdateController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/excluir[/:id]',
                            'defaults' => [
                                'controller' => Controller\DeleteController::class,
                                'action'     => 'index',
                            ],
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => function ($container) {
                return new Controller\IndexController(
                    $container->get(Service\ContatoService::class),
                    $container->get(ClienteService::class),
                );
            },
            Controller\CreateController::class => function ($container) {
                return new Controller\CreateController(
                    $container->get(Service\ContatoService::class),
                    $container->get(ClienteService::class),
                );
            },
            Controller\UpdateController::class => function ($container) {
                return new Controller\UpdateController(
                    $container->get(Service\ContatoService::class),
                    $container->get(ClienteService::class),
                );
            },
            Controller\DeleteController::class => function ($container) {
                return new Controller\DeleteController(
                    $container->get(Service\ContatoService::class)
                );
            },
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../../Application/view/layout/layout.phtml',
            'contato/contato/index' => __DIR__ . '/../view/contato/index/index.phtml',
            'error/404'               => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../Application/view/error/index.phtml',
        ],
        'template_path_stack' => [
            'contato' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => function ($serviceManager) {
                $config = $serviceManager->get('config');
                return new \Zend\Db\Adapter\Adapter($config['db']);
            },
            \Cliente\Service\ClienteService::class => function ($serviceManager) {
                return new \Cliente\Service\ClienteService(
                    $serviceManager->get('Zend\Db\Adapter\Adapter')
                );
            },
            \Contato\Service\ContatoService::class => function ($serviceManager) {
                return new \Contato\Service\ContatoService(
                    $serviceManager->get('Zend\Db\Adapter\Adapter'),
                    $serviceManager->get(ClienteService::class)
                );
            },
        ],
    ],
];
