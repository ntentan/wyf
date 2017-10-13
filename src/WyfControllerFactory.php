<?php

namespace ntentan\wyf;

use ntentan\middleware\mvc\DefaultControllerFactory;
use ntentan\Controller;
use ntentan\Model;
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
    
    private $serviceLocator;
    
    public function setupBindings(\ntentan\panie\Container $serviceLocator)
    {
        $serviceLocator->bind(View::class)->to(View::class)->asSingleton();
        $this->serviceLocator = $serviceLocator;
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
            $modelClass = $this->getClassName(substr($testedPath, 1), 'models');
            if (class_exists($controllerClass)) {
                $parameters['controller'] = $controllerClass;
                $parameters['action'] = isset($path[$i + 1]) ? $path[$i + 1] : 'index';
                $parameters['id'] = implode('/', array_slice($path, $i + 2));
                $controllerPath = str_replace('.', '/', $controllerPath);
                $parameters['controller_path'] = $controllerPath;
                $context->setParameter('controller_path', $controllerPath);
                $this->serviceLocator->bind(Model::class)->to($modelClass);
                return parent::createController($parameters);
            }
            $attempts[] = $controllerClass;
        }
    }
}
