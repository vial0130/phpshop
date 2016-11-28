<?php
namespace app\ctrl;
/**
 * shoppingCtrl short summary.
 *
 * shoppingCtrl description.
 *
 * @version 1.0
 * @author Administrator
 */
class shoppingCtrl extends \phpcms
{
    public function index()
    {
        $this->display('shopping/shopping.html');
    }
}