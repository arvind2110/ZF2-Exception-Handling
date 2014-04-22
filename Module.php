<?php

namespace ZF2ExceptionHandling;

use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Module{
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager   = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, function(MvcEvent $event) use ($serviceManager ){
        
            $exception = $event->getParam('exception');
        
            if ($exception){
                 
                do {
                    $serviceManager->get('Logger')->crit(
                        sprintf(
                            "%s:%d %s (%d) [%s]\n",
                            $exception->getFile(),
                            $exception->getLine(),
                            $exception->getMessage(),
                            $exception->getCode(),
                            get_class($exception)
                        )
                    );
                }
                while($ex = $exception->getPrevious());
        
                $response = $event->getResponse();
                $response->setHeaders(
                    $response->getHeaders()->addHeaderLine('Location', "/error-page")
                );
                $response->setStatusCode(302);
                $response->sendHeaders();
                return $response;
            }
        });
    }	
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Logger' => function ($sm) {
                    $filename = 'log_' . date('F') . '.txt';
                    $log = new Logger();
                    
                    if(!is_dir('./data/logs')){
                    	mkdir('./data/logs');
                    	chmod('./data/logs', 0777);
                    }
                    
                    $writer = new Stream('./data/logs/' . $filename);
                    $log->addWriter($writer);
                    return $log;
                }
            ),
        );
    }
}