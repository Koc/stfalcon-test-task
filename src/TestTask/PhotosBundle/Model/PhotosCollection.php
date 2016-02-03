<?php

namespace TestTask\PhotosBundle\Model;

use Pagerfanta\Pagerfanta;
use TestTask\PhotosBundle\Entity\Photo;

class PhotosCollection
{
    /**
     * @var Photo[]
     */
    public $photos = array();

    public $page;

    public $pagesCount;

    public $totalCount;

    public function __construct(Pagerfanta $pagerfanta)
    {
        $photos = $pagerfanta->getCurrentPageResults();
        $this->photos = $photos instanceof \Traversable ? iterator_to_array($photos) : $photos;
        $this->page = $pagerfanta->getCurrentPage();
        $this->pagesCount = $pagerfanta->getNbPages();
        $this->totalCount = $pagerfanta->getNbResults();
    }
}
