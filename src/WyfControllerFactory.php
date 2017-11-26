<?php

namespace ntentan\wyf;

use ntentan\middleware\mvc\DefaultControllerFactory;
use ntentan\Controller;
use ntentan\Model;
use ntentan\Context;
use ntentan\panie\Container;
use ntentan\View;
use ajumamoro\BrokerInterface;
use ntentan\utils\Text;
use ntentan\wyf\controllers\CrudController;
use ntentan\wyf\interfaces\ImportDataJobInterface;
use ntentan\wyf\jobs\ImportDataJob;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

/**
 * The default controller factory used for WYF based applications.
 *
 * @author ekow
 */
class WyfControllerFactory extends DefaultControllerFactory
{
    use ClassNameGeneratorTrait;
    
    /**
     * Keep a copy of the service container so we could later create bindings for the Model
     *
     * @var Container
     */
    private $serviceLocator;

    /**
     * Set up specific bindings needed by WYF
     *
     * @param Container $serviceLocator
     */
    public function setupBindings(Container $serviceLocator)
    {
        $serviceLocator->setup([
            View::class => [
                View::class,
                'singleton' => true,
                'calls' => ['set' => [

                ]]
            ],
            BrokerInterface::class => function($container) {
                $config = Context::getInstance()->getConfig();
                $broker = $config->get('ajumamoro.broker', 'inline');
                $brokerClass = "\\ajumamoro\\brokers\\" . Text::ucamelize($broker) . "Broker";
                return $container->resolve($brokerClass, ['config' => $config->get("ajumamoro.$broker")]);
            },
            ImportDataJobInterface::class => ImportDataJob::class,
            KeyValueStoreInterface::class => DatabaseKeyValueStore::class
        ]);
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Defers the creation of the controller to the DefaultControllerFactory extended by this class.
     *
     * @param array $parameters
     * @return Controller
     */
    private function getControllerInstance($parameters)
    {
        $controllerInstance = parent::createController($parameters);
        if(is_a($controllerInstance, CrudController::class)) {
            $modelClass = $this->getWyfClassName($controllerInstance->getWyfPackage(), 'models');
            $this->serviceLocator->bind(Model::class)->to($modelClass);
        }
        return $controllerInstance;
    }

    /**
     * Loops through the URL to extract a controller, action and other associated parameters.
     *
     * @param $parameters
     * @return Controller
     */
    private function getControllerFromPath(&$parameters)
    {
        $testedPath = '';
        $attempts = [];
        $controllerPath = "";
        $path = explode('/', $parameters['wyf_controller']);
        $context = Context::getInstance();

        foreach ($path as $i => $section) {
            $testedPath .= ".$section";
            $controllerPath .= "$section/";
            $controllerClass = "{$this->getWyfClassName(substr($testedPath, 1), 'controllers')}Controller";
            if (class_exists($controllerClass)) {
                $parameters['controller'] = $controllerClass;
                $parameters['action'] = (isset($path[$i + 1]) && $path[$i + 1] != '') ? $path[$i + 1] : 'index';
                $parameters['id'] = implode('/', array_slice($path, $i + 2));
                $controllerPath = str_replace('.', '/', $controllerPath);
                $parameters['controller_path'] = $controllerPath;
                $context->setParameter('controller_path', $controllerPath);
                return $this->getControllerInstance($parameters);
            }
            $attempts[] = $controllerClass;
        }
    }

    /**
     * Create an instance of the controller.
     *
     * @param array $parameters
     * @return Controller
     */
    public function createController(array &$parameters) : Controller
    {
        // Defer to the base default controller if we're not loading a wyf controller
        if(!isset($parameters['wyf_controller'])) {
            return parent::createController($parameters);
        }

        if(class_exists($parameters['wyf_controller'])) {
            // If the wyf controller class exists, setup bindings and load wyf controller
            $parameters['controller'] = $parameters['wyf_controller'];
            return $this->getControllerInstance($parameters);
        } else {
            // If not loop through the URL to find the container and any actions as well as parameters
            return $this->getControllerFromPath($parameters);
        }
    }
}
