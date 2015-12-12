<?php
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity */
class Game
{
    /**
     * @OneToMany(targetEntity="Score", mappedBy="game")
     */
    private $gameScore;
    private $round;
    private $gamedate;

    public function __construct() {
        $this->gameScore = new ArrayCollection();
    }
}

/** @Entity */
class Score
{
    /**
     * @ManyToOne(targetEntity="Game", inversedBy="score")
     * @JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;

    /**
     * @OneToOne(targetEntity="Team")
     * @JoinColumn(name="team_id", referencedColumnName="id")
     */
	private $team;
	private $score;
}