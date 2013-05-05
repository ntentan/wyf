<?php
namespace ntentan\plugins\wyf\lib\modules\system\notifications;

use ntentan\controllers\Controller;

class NotificationsControllerBase extends Controller
{   
    public function run()
    {
        $this->view->template = false;
        $this->view->layout = false;    
        
        $response = false;
        
        if(isset($_SESSION['notifications']) || isset($_SESSION['js']))
        {
            $response['notifications'] = isset($_SESSION['notifications']) ? $_SESSION['notifications'] : false;
            $response['js'] = isset($_SESSION['js']);
            unset($_SESSION['notifications']);
        }
        
        echo json_encode($response);
    }
    
    public function js()
    {
        $this->view->template = false;
        $this->view->layout = false;            
        
        echo $_SESSION['js'];
        unset($_SESSION['js']);
    }
}
