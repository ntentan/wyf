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
use ntentan\mvc\binders\ModelBinderRegistry;
use ntentan\panie\Container;
use ntentan\utils\Text;
use ntentan\wyf\controllers\WyfController;
use ntentan\wyf\forms\f;
use ntentan\mvc\ControllerSpec;
use ntentan\mvc\binders\DefaultModelBinder;


class WyfMiddleware extends MvcMiddleware
{
    private array $configuration;
    
    #[\Override]
    public function configure(array $configuration)
    {
        $this->configuration = $configuration;
    }
    
    private function getControllerName(string $controllerClass): string
    {
        $className = end(explode('\\', $controllerClass));
        return strtolower(substr($className, 0, strlen($className) - 10));
    }
    
    protected function getModelBinders(Container $container): ModelBinderRegistry
    {
        $modelBinder = parent::getModelBinders($container);
        $modelBinder->register(View::class, WyfViewBinder::class);
        return $modelBinder;
    }
    
    private function findControllerSpec(array $uriParts): ?array
    {
        $offset = 0;
        $namespace = "{$this->getNamespace()}\\{$this->configuration['sub_namespace']}";
        $className = null;
        
        while ($offset < count($uriParts)) {
            $className = sprintf("\\%s\\%s", $namespace,Text::ucamelize($uriParts[$offset]) . "Controller");
            if (class_exists($className)) {
                $spec = [
                    'class_name' => $className, 
                    'action' => $uriParts[$offset + 1] ?? 'main', 
                    'controller' => $uriParts[$offset]
                ];
                if (isset($uriParts[$offset + 2])) {
                    $spec['id'] = implode('/', array_slice($uriParts, $offset + 2));
                }
                return $spec;
            }
            $namespace .= $uriParts[$offset];
            $offset++;
        }
        
        return null;
    }

    #[\Override]
    protected function getControllerSpec(ServerRequestInterface $request): ControllerSpec
    {
        $uri = $request->getUri();
        $uriParts = explode('/', substr($uri->getPath(), 1));
        $dashboardClass = $this->configuration['default_class'] 
            ?? ("\\{$this->getNamespace()}\\{$this->configuration['sub_namespace']}\HomeController");
        
        $spec = match ($uriParts[0]) {
            '' => ['class_name' => $dashboardClass,'action' => 'main', 'controller' => $dashboardClass],
            'auth' => ($this->configuration['enable_auth'] ?? false) 
                ? ['class_name' => AuthController::class, 'action' => $uriParts[1] ?? null, 'controller' => 'auth']
                : null,
            default => $this->findControllerSpec($uriParts)
        };
        
        if ($spec === null) {
            throw new WyfException("Failed to load a controller for the request");
        }
        
        return new ControllerSpec($spec['class_name'], $spec['action'], $spec['controller'], $spec);
    }
    
    /**
     * Get an instance of the controller.
     * {@inheritDoc}
     * @see \ntentan\mvc\MvcMiddleware::getControllerInstance()
     */
    protected function getControllerInstance(Container $container, ControllerSpec $controllerSpec)
    {
        $instance = $container->get($controllerSpec->getControllerClass());
        if ($instance instanceof WyfController) {
            $instance->setup($controllerSpec, $container->get(DefaultModelBinder::class));
        }
        return $instance;
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
        
        f::init($container->get(Templates::class));
        $templateFileResolver = $container->get(TemplateFileResolver::class);
        $viewsPath = realpath(__DIR__ . "/../views");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/controllers");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/layouts");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/shared");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/crud");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/forms");
        return parent::run($request, $response, $next);
    }
}
