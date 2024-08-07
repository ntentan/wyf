<?php
namespace ntentan\wyf;

use ntentan\mvc\MvcMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use ntentan\wyf\controllers\AuthController;
use ntentan\honam\TemplateFileResolver;
use ntentan\mvc\View;
use ntentan\honam\Templates;
use ntentan\http\StringStream;
use ntentan\http\Uri;


class WyfMiddleware extends MvcMiddleware
{
    private array $configuration;
    
    #[\Override]
    public function configure(array $configuration)
    {
        $this->configuration = $configuration;
        if (!isset($this->configuration['namespace'])) {
            throw new WyfException("Please provide the base namespace of your WYF controller classes.");
        }
    }
    
    private function getControllerName(string $controllerClass): string
    {
        $className = end(explode('\\', $controllerClass));
        return strtolower(substr($className, 0, strlen($className) - 10));
    }

    #[\Override]
    protected function getController(ServerRequestInterface $request): array
    {
        $uri = $request->getUri();
        $uriParts = explode('/', substr($uri->getPath(), 1));
        
        if(($this->configuration['enable_auth'] ?? false) && $uriParts[0] == 'auth') {
            return ['class_name' => AuthController::class, 'action' => $uriParts[1], 'controller' => $uriParts[0]];
        } else if (count($uriParts) == 1 && $uriParts[0] == '') {
            $className = $this->configuration['default_class'] ?? $this->configuration['namespace'] . "DashboardController";
            return [
                'class_name' => $className, 'action' => 'main', 'controller' => $this->getControllerName($className)
            ];
        }
        throw new WyfException("Failed to load a controller for the request");
    }
    
    private function getMenu(string $path): array
    {
        $pathFrontier = [$path];
        $menu = [];
        
        while(!empty($pathFrontier)) {
            
        }
        
        return $menu;
    }
    
    #[\Override]
    public function run (ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $container = $this->getServiceContainer($request, $response);
        $container->setup([
            View::class => [
                function($container) {
                    $instance = new View($container->get(Templates::class), $container->get(StringStream::class));
                    $instance->set('wyf_app_name', $this->configuration['application_name'] ?? 'WYF Framework Application');
                    $instance->set('wyf_menu', $this->configuration['navigation']);
                    $instance->set('ntentan_uri_prefix', $container->get(Uri::class)->getPrefix());
                    return $instance;
                }
            ]
        ]);
        $templateFileResolver = $container->get(TemplateFileResolver::class);
        $viewsPath = realpath(__DIR__ . "/../views");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/controllers");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/layouts");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/shared");
        return parent::run($request, $response, $next);
    }
}
