<?php

class DownloadsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Downloads');
        $this->loadCustomTrans("downloads");
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
