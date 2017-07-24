<?php

namespace ntentan\wyf\controllers;

use ntentan\Controller;
use ntentan\Context;
use ntentan\View;
use ntentan\utils\Text;

/**
 * Base controller for all WYF application modules you want to appear in the menu.
 */
class WyfController extends Controller
{

    private $permissions = array();

    /**
     * 
     * @var integer
     */
    public $weight;
    private $package;
    private $name;
    private $path;
    private $titleBase;
    private $view;

    public function __construct(Context $context)
    {
        $this->view = $context->getContainer()->resolve(View::class);
        $app = $context->getApp();
        $appName = $app->getName();
        $this->view->set('route_breakdown', explode('/', $context->getRouter()->getRoute()));
        $this->view->set('wyf_app_name', $appName);
        $this->view->set('wyf_logout_url', $context->getUrl('auth/logout'));
        $this->titleBase = "{$appName}";
        $class = get_class($this);
        $namespace = $context->getNamespace();
        if (preg_match(
                        "/$namespace\\\\app\\\\(?<base>.*)\\\\controllers\\\\(?<name>.*)Controller/", $class, $matches
                )) {
            $this->package = strtolower(Text::deCamelize(str_replace("\\", ".", $matches['base'])) . "." . Text::deCamelize($matches['name']));
            $this->name = str_replace("_", " ", Text::deCamelize($matches['name']));
            $this->path = str_replace('.', '/', $this->package);
        }
        $this->view->set('menu', $app->getMenu());
    }

    protected function setTitle($title)
    {
        $this->view->set('wyf_title', $title);
    }

    public function getDescription()
    {
        
    }

    public function addPermission($code, $description)
    {
        $this->permissions[$code] = $description;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getWyfName()
    {
        return $this->name;
    }

    public function getWyfPackage()
    {
        return $this->package;
    }

    public function getWyfPath()
    {
        return $this->path;
    }

}
