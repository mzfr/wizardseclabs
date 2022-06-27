<?php

class CopyrightController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter(array('main'));
        Phalcon\Tag::setTitle('Copyright');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
