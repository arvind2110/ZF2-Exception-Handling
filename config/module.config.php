<?php
return array(
    'router' => array(
        'routes' => array(
            'error-page' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/error-page',
                    'defaults' => array(
                        '__NAMESPACE__' => 'ZF2ExceptionHandling\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'ZF2ExceptionHandling\Controller\Index' => 'ZF2ExceptionHandling\Controller\IndexController'
        )
    ),
    'view_manager' => array(
        'template_map' => array(
            'zf2-exception-handling/index/index' => __DIR__ . '/../view/zf2-exception-handling/index/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    )
);
