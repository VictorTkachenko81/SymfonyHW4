<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\GameScore;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GameRepository")
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="championship", type="string", length=50)
     */
    private $championship;

    /**
     * @var string
     *
     * @ORM\Column(name="round", type="string", length=50)
     */
    private $round;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gamedate", type="date")
     */
    private $gamedate;

    /**
     * @var string
     *
     * @ORM\Column(name="referee", type="string", length=255)
     */
    private $referee;

    /**
     * @var string
     *
     * @ORM\Column(name="stadium", type="string", length=255)
     */
    private $stadium;

    /**
     * @ORM\OneToMany(targetEntity="GameScore", mappedBy="game", cascade={"persist"})
     */
    private $scores;


    public function __construct() {
        $this->scores = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set championship
     *
     * @param string $championship
     *
     * @return Game
     */
    public function setChampionship($championship)
    {
        $this->championship = $championship;

        return $this;
    }

    /**
     * Get championship
     *
     * @return string
     */
    public function getChampionship()
    {
        return $this->championship;
    }

    /**
     * Set round
     *
     * @param string $round
     *
     * @return Game
     */
    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Get round
     *
     * @return string
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Set gamedate
     *
     * @param \DateTime $gamedate
     *
     * @return Game
     */
    public function setGamedate($gamedate)
    {
        $this->gamedate = $gamedate;

        return $this;
    }

    /**
     * Get gamedate
     *
     * @return \DateTime
     */
    public function getGamedate()
    {
        return $this->gamedate;
    }

    /**
     * Set referee
     *
     * @param string $referee
     *
     * @return Game
     */
    public function setReferee($referee)
    {
        $this->referee = $referee;

        return $this;
    }

    /**
     * Get referee
     *
     * @return string
     */
    public function getReferee()
    {
        return $this->referee;
    }

    /**
     * Set stadium
     *
     * @param string $stadium
     *
     * @return Game
     */
    public function setStadium($stadium)
    {
        $this->stadium = $stadium;

        return $this;
    }

    /**
     * Get stadium
     *
     * @return string
     */
    public function getStadium()
    {
        return $this->stadium;
    }

    /**
     * Add score
     *
     * @param GameScore $score
     *
     * @return Game
     */
    public function addScore(GameScore $score)
    {
        $score->setGame($this);
        $this->scores[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param GameScore $score
     */
    public function removeScore(GameScore $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }
}
