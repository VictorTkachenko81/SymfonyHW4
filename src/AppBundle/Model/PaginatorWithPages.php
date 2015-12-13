<?php

namespace AppBundle\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorWithPages extends Paginator
{
    private $currentPage;

    private $countPages;

    public function getCurrentPage()
    {
        $firstResult = $this->getQuery()->getFirstResult();
        $maxResults = $this->getQuery()->getMaxResults();
        $currentPage = $firstResult / $maxResults + 1;

        return $currentPage;
    }

    public function getCountPages()
    {
        $countResults = $this->count();
        $maxResults = $this->getQuery()->getMaxResults();
        $countPages = ceil($countResults / $maxResults);

        return $countPages;
    }
}