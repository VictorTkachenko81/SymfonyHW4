<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
    public function getTeamGames($team)
    {
        return $this->createQueryBuilder('g')
            ->leftjoin('g.scores', 's')
            ->where('s in (?1)')
            ->setParameter(1, $team->getGameScore())
            ->getQuery()
            ->getResult();
    }

    public function getScores($games)
    {
        return $this->createQueryBuilder('g')
            ->select('g, s, t')
            ->leftjoin('g.scores', 's')
            ->join('s.team', 't')
            ->where('g in (?1)')
            ->setParameter(1, $games)
            ->getQuery()
            ->getResult();
    }
}
