<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\http\filters\Get;


/**
 * Generate dashboards for WYF applications.
 */
class DashboardController extends WyfController
{
    #[Get]
    public function main(View $view): View
    {
        $view->setTemplate('dashboard_main.tpl.php');
        return $view;
    }

    public function getLabel(): string
    {
        return "Dashboard";
    }
}

