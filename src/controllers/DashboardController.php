<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;
use ntentan\mvc\Action;


/**
 * Generate dashboards for WYF applications.
 */
class DashboardController
{
    #[Action]
    public function main(View $view): View
    {
        $view->setTemplate('dashboard_main.tpl.php');
        return $view;
    }
}

