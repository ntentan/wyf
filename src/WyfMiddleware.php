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
use ntentan\sessions\SessionStore;


class WyfMiddleware extends MvcMiddleware
{
    private array $configuration;
    
    #[\Override]
    public function configure(array $configuration): void
    {
        parent::configure($configuration);
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

    private static function isId(string $id): bool
    {
        return is_numeric($id)
            ||  preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $id) === 1;
    }
    
    private function findControllerSpec(array $uriParts): ?array
    {
        $offset = 0;
        $namespace = "{$this->getNamespace()}\\modules" ;
        $controllerPath = "";
        $className = null;
        $bestSpec = [];
        $hierarchy = [];
        $breadCrumb = [];
        $foundSpecs = [];
        
        while ($offset < count($uriParts)) {
            $className = sprintf("\\%s\\%s\\%s", $namespace,$uriParts[$offset], Text::ucamelize($uriParts[$offset]) . "Controller");
            $controllerPath .= "/{$uriParts[$offset]}";
            if (class_exists($className)) {
                $spec = [
                    'class_name' => $className, 
                    'action' => isset($uriParts[$offset + 1]) && $uriParts[$offset + 1] != '' && !self::isId($uriParts[$offset + 1]) ? $uriParts[$offset + 1] : 'main',
                    'controller' => $uriParts[$offset],
                    'controller_path' => $controllerPath,
                ];
                if (count($foundSpecs) > 0 && isset($uriParts[$offset + 1]) && is_numeric($uriParts[$offset + 1])) {
                    $hierarchy[end($foundSpecs)['controller']] = $uriParts[$offset + 1];
                    $spec['controller_path'] = "{$spec['controller_path']}/{$uriParts[$offset + 1]}";
                    $spec['hierarchy'] = $hierarchy;
                    $spec['action'] = isset($uriParts[$offset + 2]) && !self::isId($uriParts[$offset + 2]) ? $uriParts[$offset + 2] : 'main';
                    if (isset($uriParts[$offset + 3])) {
                        $spec['id'] = implode('/', array_slice($uriParts, $offset + 3));
                    }
                } else if (isset($uriParts[$offset + 2])) {
                    $spec['id'] = implode('/', array_slice($uriParts, $offset + 2));
                }
                $foundSpecs[]= $spec;
                $breadCrumb[]= [
                    'url' => implode("/", array_slice($uriParts, 0, $offset + 1)),
                    'controller' => $className,
                ];
            } else {
                $breadCrumb[] = [
                    'url' => implode("/", array_slice($uriParts, 0, $offset + 1)),
                    'label' => Text::deCamelize($uriParts[$offset])
                ];
            }

            if (!self::isId($uriParts[$offset])) {
                $namespace .= '\\' . $uriParts[$offset];
            }

            $offset++;
        }

        $bestSpec = array_pop($foundSpecs);
        $bestSpec['breadcrumb_trail'] = $breadCrumb;
        
        return $bestSpec;
    }

    #[\Override]
    protected function getControllerSpec(ServerRequestInterface $request): ControllerSpec
    {
        $uri = $request->getUri();
        $namespace = $this->getNamespace();
        $uriParts = array_filter(explode('/', substr($uri->getPath(), 1)), fn($x) => $x !== '');
        $defaultModule = $this->configuration['default_module'] ?? "dashboard";
        $controllerName = Text::ucamelize($defaultModule);
        $dashboardClass = "\\{$this->getNamespace()}\\modules\\{$defaultModule}\\{$controllerName}Controller";

        $spec = match ($uriParts) {
            [], [''] => [
                'class_name' => $dashboardClass,
                'action' => 'main', 
                'controller' => $controllerName,
                'breadcrumb_trail' => [
                    ['url' => 'dashboard', 'label' => 'Dashboard']
                ]
            ],
            ['auth', 'login'], ['auth', 'logout'] => ($this->configuration['enable_auth'] ?? false)
                ? ['class_name' => AuthController::class, 'action' => $uriParts[1] ?? null, 'controller' => 'auth']
                : null,
            default => $this->findControllerSpec($uriParts)
        };
        
        if ($spec === null) {
            throw new WyfException("Failed to load a controller for the request");
        }
        
        return new WyfControllerSpec($spec['class_name'], $spec['action'], $spec['controller'], $spec);
    }
    
    /**
     * Get an instance of the controller.
     * {@inheritDoc}
     * @see \ntentan\mvc\MvcMiddleware::getControllerInstance()
     */
    protected function getControllerInstance(Container $container, ControllerSpec &$controllerSpec): object
    {
        $instance = $container->get($controllerSpec->getControllerClass());
        $processedBreadCrumb = [];

        if ($instance instanceof WyfController) {
            $instance->setup($controllerSpec, $container->get(DefaultModelBinder::class), $this->getContext());
            $instance->setConfig($this->configuration);
            foreach($controllerSpec->getParameter('breadcrumb_trail') as $crumb) {
                if (isset($crumb['label'])) {
                    $crumb['label'] = ucwords($crumb['label']);
                    $processedBreadCrumb[] = $crumb;
                } else if (isset($crumb['controller']) && get_class($instance) === substr($crumb['controller'], 1)) {
                    $processedBreadCrumb[] = [
                        'url' => $crumb['url'],
                        'label' => ucwords($instance->getLabel())
                    ];
                }
            }
            $controllerSpec->stylesheets = $instance->stylesheets;
        }

        $controllerSpec->breadCrumbTrail = $processedBreadCrumb;
        return $instance;
    }
    
    private function normalizeNavigationPath(string $parent, string $module): string
    {
        $module = trim($module, '/');
        $path = rtrim($parent, '/') . ($module !== '' ? "/{$module}" : '');
        return $path === '' ? '/' : $path;
    }

    private function findBestNavigationMatch(array $items, string $parent, string $rawPath, string $effectivePath): ?string
    {
        $bestMatch = null;

        foreach ($items as $item) {
            $itemPath = strtolower($this->normalizeNavigationPath($parent, $item['module'] ?? ''));

            if ($itemPath === '/') {
                $isMatch = $rawPath === '/';
            } else {
                $isMatch = $effectivePath === $itemPath || str_starts_with($effectivePath, "{$itemPath}/");
            }

            if ($isMatch && ($bestMatch === null || strlen($itemPath) > strlen($bestMatch))) {
                $bestMatch = $itemPath;
            }

            if (isset($item['children']) && is_array($item['children'])) {
                $childMatch = $this->findBestNavigationMatch(
                    $item['children'],
                    rtrim($itemPath, '/') . '/',
                    $rawPath,
                    $effectivePath
                );
                if ($childMatch !== null && ($bestMatch === null || strlen($childMatch) > strlen($bestMatch))) {
                    $bestMatch = $childMatch;
                }
            }
        }

        return $bestMatch;
    }

    /**
     * @return array{0: array, 1: bool}
     */
    private function applyActiveNavigationState(array $items, string $parent, ?string $bestMatch): array
    {
        $branchActive = false;
        $resolved = [];

        foreach ($items as $item) {
            $itemPath = strtolower($this->normalizeNavigationPath($parent, $item['module'] ?? ''));

            $childActive = false;
            if (isset($item['children']) && is_array($item['children'])) {
                [$item['children'], $childActive] = $this->applyActiveNavigationState(
                    $item['children'],
                    rtrim($itemPath, '/') . '/',
                    $bestMatch
                );
            }

            $isSelected = !$childActive && $bestMatch !== null && $itemPath === $bestMatch;
            $item['selected'] = $isSelected;
            $item['expanded'] = $childActive || ($isSelected && isset($item['children']) && is_array($item['children']));

            if ($isSelected || $childActive) {
                $branchActive = true;
            }

            $resolved[] = $item;
        }

        return [$resolved, $branchActive];
    }

    private function resolveNavigation(array $navigation, string $currentPath): array
    {
        $rawPath = strtolower('/' . trim($currentPath, '/'));
        $defaultModule = strtolower(trim($this->configuration['default_module'] ?? 'dashboard', '/'));
        $effectivePath = ($rawPath === '/' && $defaultModule !== '') ? "/{$defaultModule}" : $rawPath;

        $bestMatch = $this->findBestNavigationMatch($navigation, '/', $rawPath, $effectivePath);
        [$resolved] = $this->applyActiveNavigationState($navigation, '/', $bestMatch);
        return $resolved;
    }

    #[\Override]
    public function run (ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        $container = $this->getServiceContainer($request, $response);
        $container->setup([
            View::class => [
                function($container) use ($request) {
                    $instance = new View($container->get(Templates::class), $container->get(StringStream::class));
                    $navigation = $this->resolveNavigation(
                        $this->configuration['navigation'] ?? [],
                        $request->getUri()->getPath()
                    );
                    $instance->set('wyf_app_name', $this->configuration['application_name'] ?? 'WYF Framework Application');
                    $instance->set('wyf_menu', $navigation);
                    $instance->set('wyf_logo', $this->configuration['application_logo'] ?? '/images/logo.png');
                    $instance->set('wyf_navigation', $navigation);
                    $instance->set('ntentan_uri_prefix', $container->get(Uri::class)->getPrefix());
                    $instance->set('wyf_user', $container->get(SessionStore::class)->get('user'));
                    return $instance;
                }
            ]
        ]);


        f::init($container->get(Templates::class));
        $templateFileResolver = $container->get(TemplateFileResolver::class);
        $viewsPath = realpath(__DIR__ . "/../views");
        $templateFileResolver->prependToPathHierarchy("$viewsPath/forms");
        $templateFileResolver->prependToPathHierarchy("$viewsPath/crud");
        $templateFileResolver->prependToPathHierarchy("$viewsPath/shared");
        $templateFileResolver->prependToPathHierarchy("$viewsPath/layouts");
        $templateFileResolver->prependToPathHierarchy("$viewsPath/controllers");
        return parent::run($request, $response, $next);
    }
}
