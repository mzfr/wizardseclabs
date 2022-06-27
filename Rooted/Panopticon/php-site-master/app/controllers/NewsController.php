<?php

class NewsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter(array('main'));
        parent::initialize();
    }

    public function index()
    {
        return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
    }

    private function _getSanizitedTitleId()
    {
        return preg_replace('/[^a-z0-9\-]/', '', $this->dispatcher->getParam('title'));
    }

    public function showAction()
    {

        $title = $this->_getSanizitedTitleId();

        $language = $this->session->get("language");

        $exists = $this->view->getCache()->exists($language.$title);
        if (!$exists) {

            $new = News::findFirst("short_title='$title'");
            if ($new == false) {
                $this->flash->error('The post cannot be found');
                return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
            }

            $activeYear = $this->dispatcher->getParam('year', "int");

            Phalcon\Tag::setTitle($new->title);

            $this->view->setVar("new", $new);
            $this->view->setVar("activeYear", $activeYear);
            $this->view->setVar("years", News::count(array('group' => 'year')));
        }

        $this->view->cache(array("lifetime" => 86400, "key" => $language.$title));
    }

    public function showYearAction()
    {

        $activeYear = $this->dispatcher->getParam('year', "int");

        $exists = $this->view->getCache()->exists($activeYear);
        if (!$exists) {

            Phalcon\Tag::setTitle('News');

            $this->view->setVar("news", News::find(array("year='$activeYear'", "order" => "published DESC")));
            $this->view->setVar("activeYear", $activeYear);
            $this->view->setVar("years", News::count(array('group' => 'year')));

        }

        $this->view->cache(array("lifetime" => 86400, "key" => $activeYear));

    }

    public function taggedAction($tag)
    {
        $tag = $this->filter->sanitize($tag, "alphanum");

        $exists = $this->view->getCache()->exists($tag);
        if (!$exists) {

            Phalcon\Tag::setTitle('Tagged '.$tag);

            $category = Categories::findFirst("name='$tag'");
            if ($category == false) {
                return $this->dispatcher->forward(array('controller' => 'index', 'action' => 'index'));
            }

            $news = array();
            $newsCategories = NewsCategories::find(array("categories_id='".$category->id."'"));
            foreach ($newsCategories as $newCategory) {
                $news[] = $newCategory->getNews();
            }

            $this->view->setVar("activeYear", 0);
            $this->view->setVar("tag", $tag);
            $this->view->setVar("news", $news);
            $this->view->setVar("years", News::count(array('group' => 'year')));

        }

        $this->view->cache(array("lifetime" => 86400, "key" => $tag));
    }
}
