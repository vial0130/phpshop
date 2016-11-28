<?php
namespace app\ctrl;
/**
 * listCtrl short summary.
 *
 * listCtrl description.
 *
 * @version 1.0
 * @author Administrator
 */
class listCtrl extends \phpcms
{
    public function index()
    {
        $this->display('list/class.html');
    }

    public function screen()
    {
        $this->display('list/screen.html');
    }
}