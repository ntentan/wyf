<?php
namespace ntentan\plugins\wyf\lib\modules\system\notifications;

use ntentan\controllers\Controller;

class NotificationsControllerBase extends Controller
{
    public function run()
    {
        $this->view->template = false;
        $this->view->layout = false;
        if(isset($_SESSION['notifications']))
        {
            echo json_encode($_SESSION['notifications']);
            unset($_SESSION['notifications']);
        }
        else
        {
            echo 'false';
        }
    }
}
