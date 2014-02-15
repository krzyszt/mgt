<?php

namespace TankAdmin;

return array(
    'controllers' => array(
        'invokables' => array(
            'TankAdmin\Controller\Index' => 'TankAdmin\Controller\IndexController',            
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'em' => 'TankAdmin\Controller\Plugin\EntityManagerPlugin',
        )
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'TankAdmin\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'tankadmin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'TankAdmin\Controller',
                        'controller' => 'TankAdmin\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'tankadmin' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
    'module_layouts' => array(
        'TankAdmin' => 'layout/layout.phtml',
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__.'_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/'.__NAMESPACE__.'/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__.'\Entity' => __NAMESPACE__.'_driver'
                )
            )
        )
    ),
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class' => 'TankAdmin\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'TankAdmin\Entity\Role',
            ),
        ),
        // Guard listeners to be attached to the application event manager
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'home', 'roles' => array('guest', 'user')),
                array('route' => 'zfcuser')
            ),
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'TankAdmin\Controller\Index',
                    'action' => 'index',
                    'roles' => array('guest','user','admin'),
                ),
            ),
        ),
    ),
    
);