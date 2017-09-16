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
    use ClassNameGeneratorTrait;
    
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
        
        foreach ($path as $i => $section) {
            $testedPath .= ".$section";
            $controllerPath .= "$section/";
            $controllerClass = "{$this->getClassName(substr($testedPath, 1), 'controllers')}Controller";
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
