<?php

namespace ntentan\wyf;

use ntentan\middleware\mvc\DefaultControllerFactory;
use ntentan\Controller;
use ntentan\utils\Text;
use ntentan\Context;
use ntentan\View;

/**
 * Description of WycControllerFactory
 *
 * @author ekow
 */
class WyfControllerFactory extends DefaultControllerFactory
{
    private function getClassName($wyfPath) 
    {
        $wyfPathParts = explode('.', $wyfPath);
        $name = Text::ucamelize(array_pop($wyfPathParts));
        $base = (count($wyfPathParts) ? '\\' : '') . implode('\\', $wyfPathParts);
        return "\\app$base\\controllers\\{$name}Controller";
    }
    
    public function setupBindings(\ntentan\panie\Container $serviceLocator)
    {
        $serviceLocator->bind(View::class)->to(View::class)->asSingleton();
    }

    public function createController(array &$parameters) : Controller
    {
        if(!isset($parameters['wyf_controller'])) {
            return parent::createController($parameters);
        }
        
        $testedPath = '';
        $attempts = [];
        $controllerPath = "";        
        $path = explode('/', $parameters['wyf_controller']);
        $context = Context::getInstance();
        $namespace = $context->getNamespace();
        
        foreach ($path as $i => $section) {
            $testedPath .= ".$section";
            $controllerPath .= "$section/";
            $controllerClass = "$namespace{$this->getClassName(substr($testedPath, 1))}";
            if (class_exists($controllerClass)) {
                $parameters['controller'] = $controllerClass;
                $parameters['action'] = isset($path[$i + 1]) ? $path[$i + 1] : 'index';
                $parameters['id'] = implode('/', array_slice($path, $i + 2));
                $controllerPath = str_replace('.', '/', $controllerPath);
                $parameters['controller_path'] = $controllerPath;
                $context->setParameter('controller_path', $controllerPath);
                //$controllerInstance = $this->context->getContainer()->resolve($controllerClass);
                return parent::createController($parameters);
            }
            $attempts[] = $controllerClass;
        }
        
    }
}
