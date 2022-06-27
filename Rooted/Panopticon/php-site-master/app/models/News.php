<?php

class News extends \Phalcon\Mvc\Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $short_title;

    /**
     * @var string
     */
    public $title;

    /**
     * @var integer
     */
    public $published;

    /**
     * @var integer
     */
    public $updated;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string
     */
    public $content;

    public function getUri()
    {
        return date('Y/m', $this->published).'/'.$this->short_title;
    }

    public function getPublishedDate()
    {
        return strftime('%d %b %Y', $this->published);
    }

    public function getCategoriesLinks($translate)
    {
        $categories = array();
        foreach ($this->getNewsCategories() as $newCategory) {
            $category = $newCategory->getCategories();
            $categories[] = Phalcon\Tag::linkTo('news/tagged/'.$category->name, $translate[$category->name]);
        }

        return join(', ', $categories);
    }

    public function initialize()
    {
        $this->hasMany('id', 'NewsCategories', 'news_id');
    }
}
