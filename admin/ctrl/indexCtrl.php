<?php
namespace admin\ctrl;
class indexCtrl extends commonCtrl
{
    public function index()
    {
        $this->display('index/index.html');
    }

    public function main()
    {
        $this->display('index/main.html');
    }

}

?>