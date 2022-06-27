<?php

class LinksController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Links');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
