<?php

namespace ntentan\wyf;

use ntentan\interfaces\ResourceLoaderInterface;
use ntentan\panie\InjectionContainer;
use ntentan\interfaces\ControllerClassResolverInterface;

class WyfLoader implements ResourceLoaderInterface
{
    //put your code here
    public function load($parameters) 
    {
        $path = explode('/', $parameters['wyf_controller']);
        $resolver = InjectionContainer::singleton(ControllerClassResolverInterface::class);
        $testedPath = '';
        $attempts = [];
        
        foreach($path as $i => $section) {
            $testedPath .= ".$section";
            $controllerClass = $resolver->getControllerClassName(substr($testedPath, 1));
            if(class_exists($controllerClass)) {
                $action=isset($path[$i + 1]) ? $path[$i + 1] : 'index';
                $parameters['id'] = implode('/', array_slice($path, $i + 2));
                InjectionContainer::resolve($controllerClass)->executeControllerAction($action, $parameters);
                return ['success' => true];
            }
            $attempts[] = $controllerClass;
        }
        return ['success' => false, 'message' => "Failed to find any of the following classes [" . implode(', ',$attempts) . "]"];
    }
}
