<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cliente;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'clientes' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/clientes',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cliente.create' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/cliente/create',
                    'defaults' => [
                        'controller' => Controller\CreateController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cliente.update' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cliente/atualizar[/:id]',
                    'defaults' => [
                        'controller' => Controller\UpdateController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'cliente.delete' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cliente/excluir[/:id]',
                    'defaults' => [
                        'controller' => Controller\DeleteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => function ($container) {
                return new Controller\IndexController(
                    $container->get(Service\ClienteService::class)
                );
            },
            Controller\CreateController::class => function ($container) {
                return new Controller\CreateController(
                    $container->get(Service\ClienteService::class)
                );
            },
            Controller\UpdateController::class => function ($container) {
                return new Controller\UpdateController(
                    $container->get(Service\ClienteService::class)
                );
            },
            Controller\DeleteController::class => function ($container) {
                return new Controller\DeleteController(
                    $container->get(Service\ClienteService::class)
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
            'cliente/cliente/index' => __DIR__ . '/../view/cliente/index/index.phtml',
            'error/404'               => __DIR__ . '/../../Application/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../Application/view/error/index.phtml',
        ],
        'template_path_stack' => [
            'cliente' => __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Zend\Db\Adapter\Adapter' => function ($serviceManager) {
                $config = $serviceManager->get('config');
                return new \Zend\Db\Adapter\Adapter($config['db']);
            },
            \Cliente\Service\ClienteService::class => function ($serviceManager) {
                return new \Cliente\Service\ClienteService($serviceManager->get('Zend\Db\Adapter\Adapter'));
            },
        ],
    ],
];
