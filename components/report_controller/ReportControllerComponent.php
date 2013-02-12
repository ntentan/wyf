<?php
namespace ntentan\plugins\wyf\components\report_controller;

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class ReportControllerComponent extends Component
{
    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/report_controller'));
    }
    
    public function setParams()
    {
        
    }
    
    public function run()
    {
        
    }
}
