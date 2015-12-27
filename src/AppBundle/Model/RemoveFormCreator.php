<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 27.12.15
 * Time: 18:49
 */

namespace AppBundle\Model;

class RemoveFormCreator
{
    private $data;

    public function getData(array $items)
    {
        foreach($items as $item) {
            $data[$item->getId()] = 'dd';
        }
        return $data;
    }
}