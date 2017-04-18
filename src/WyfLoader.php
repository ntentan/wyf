<?php

namespace ntentan\wyf;

use ntentan\interfaces\ResourceLoaderInterface;
use ntentan\interfaces\ControllerClassResolverInterface;
use ntentan\Context;

class WyfLoader implements ResourceLoaderInterface {
    
    private $context;
    
    public function __construct(Context $context) {
        $this->context = $context;
    }

    public function load($parameters) {
        $path = explode('/', $parameters['wyf_controller']);
        $resolver = $this->context
            ->getContainer()
            ->singleton(ControllerClassResolverInterface::class);
        $testedPath = '';
        $attempts = [];
        $controllerPath = "";

        foreach ($path as $i => $section) {
            $testedPath .= ".$section";
            $controllerPath .= "/$section";
            $controllerClass = $resolver->getControllerClassName(substr($testedPath, 1));
            if (class_exists($controllerClass)) {
                $action = isset($path[$i + 1]) ? $path[$i + 1] : 'index';
                $parameters['id'] = implode('/', array_slice($path, $i + 2));
                $parameters['controller_path'] = $controllerPath;
                //Ntentan::getRouter()->setVar('controller_path', $controllerPath);
                $controllerInstance = $this->context->getContainer()->resolve($controllerClass);
                $controllerInstance->executeControllerAction($action, $parameters, $this->context);
                return ['success' => true];
            }
            $attempts[] = $controllerClass;
        }
        return [
            'success' => false, 
            'message' => "Failed to find any of the following classes [" . implode(', ', $attempts) . "]"
        ];
    }

}
