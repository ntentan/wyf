<?php
namespace ntentan\plugins\wyf\components\report_controller;

require_once "dashy/lib/Dashy.php";

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

class ReportControllerComponent extends Component
{
    private $report;
    private $reportParams;
    
    public function __construct($params) 
    {
        // Use the default reporting settings and load the report file
        if(is_string($params['report']))
        {
            $this->report = \Dashy::loadReport($params['report']);
        }
        // Present a customized report based on ntentan
        else
        {
            $this->report = \Dashy::loadReport(
                array(
                    'title' => $params['report']['title'],
                    'data_sources' => array(
                        'ntentan_data' => array(
                            'type' => 'ntentan',
                            'parameters' => $params['report']['data']
                        )
                    )
                )
            );
        }
        $this->reportParams = $params['parameters'];
    }
    
    public function init()
    {
        TemplateEngine::appendPath(Ntentan::getPluginPath('wyf/views/report_controller'));
    }
    
    public function run()
    {
        $this->set('report_title', $this->report->getTitle());
        $this->set('action_route', Ntentan::getUrl("{$this->route}/generate"));
        $this->view->template = 'report_setup.tpl.php';
    }
    
    public function generate()
    {
        $this->view->layout = false;
        $this->view->template = false;
        echo $this->report->render($_POST['format'], $this->reportParams);
    }
}
