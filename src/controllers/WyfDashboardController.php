<?php
namespace ntentan\wyf\controllers;

use ntentan\mvc\View;

class WyfDashboardController
{
    public function main(View $view): View
    {
        $view->setTemplate('dashboard_main.tpl.php');
        return $view;
    }
}

