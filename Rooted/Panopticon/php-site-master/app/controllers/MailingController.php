<?php

class MailingController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Mailing Lists');
        parent::initialize();
    }

    public function indexAction()
    {
    }
}
