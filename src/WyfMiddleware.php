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


class WyfMiddleware extends MvcMiddleware
{
    private array $configuration;
    
    #[\Override]
    public function configure(array $configuration)
    {
        $this->configuration = $configuration;
    }

    #[\Override]
    protected function getController(ServerRequestInterface $request): array
    {
        $uri = $request->getUri();
        $uriParts = explode('/', substr($uri->getPath(), 1));
        if(($this->configuration['enable_auth'] ?? false) && $uriParts[0] == 'auth') {
            return ['class_name' => AuthController::class, 'action' => $uriParts[1], 'controller' => $uriParts[0]];
        }
        throw new WyfException("Failed to load a controller for the request");
    }
    
    #[\Override]
    public function run (ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $container = $this->getServiceContainer($request, $response);
        $container->setup([
            View::class => [
                function($container) {
                    $instance = new View($container->get(Templates::class), $container->get(StringStream::class));
                    $instance->set('wyf_app_name', $this->configuration['app_name'] ?? 'WYF APP');
                    return $instance;
                }
            ]
        ]);
        $templateFileResolver = $container->get(TemplateFileResolver::class);
        $viewsPath = __DIR__ . "/../views";
        $templateFileResolver->appendToPathHierarchy("$viewsPath/auth");
        $templateFileResolver->appendToPathHierarchy("$viewsPath/layouts");
        return parent::run($request, $response, $next);
    }
}
