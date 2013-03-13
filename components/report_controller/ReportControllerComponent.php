<?php
namespace ntentan\plugins\wyf\components\report_controller;

require_once "dashy/lib/Dashy.php";

use ntentan\controllers\components\Component;
use ntentan\views\template_engines\TemplateEngine;
use ntentan\Ntentan;

/**
 * 
 * 
 * @author ekow
 * 
 */
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
                    ),
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
        $this->set('report_filters', $this->report->getDataSource('ntentan_data')->getMetaData());
        $this->view->template = 'report_setup.tpl.php';
    }
    
    public function generate()
    {
        $this->view->layout = false;
        $this->view->template = false;
        
        $reportLayout = array(
            array(
                'type' => 'standard_header'
            ),
            array(
                'type' => 'table'
            )
        );
        
        $this->report->setLayout($reportLayout);
        $rawFilters = array();
        $filters = array();
        
        // Evaluate filters
        foreach($_POST as $key => $value)
        {
            $matched = preg_match("/(filter_)(?<index>\d)(_)(?<type>column|operator|operand)/", $key, $matches);
            if($matched)
            {
                $rawFilters[$matches['index']][$matches['type']] = $value;
                unset($_POST[$key]);
            }
        }
        
        
        if(count($rawFilters) > 0)
        {
            foreach($rawFilters as $index => $filter)
            {
                $filters[$filter['column']][] = array(
                    'operator' => $rawFilters[$index]['operator'],
                    'operand' => $rawFilters[$index]['operand']
                );
            }
            $this->report->setFilters($filters);
        }
        
        $format = $_POST['output'];
        unset($_POST['output']);
        
        echo $this->report->render(
            $format == '' ? 'pdf' : $format, 
            array_merge($_POST, $this->reportParams)
        );
    }
}

