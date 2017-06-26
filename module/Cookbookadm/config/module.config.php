<?php
namespace Cookbookadm;

use Zend\Router\Http\Segment;

return [

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'cookbookadm' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/cookbookadm[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CookbookadmController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'cookbookadm' => __DIR__ . '/../view',
        ],
    ],
];