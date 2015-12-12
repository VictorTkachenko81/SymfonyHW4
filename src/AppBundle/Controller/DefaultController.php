<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("AppBundle::index.html.twig")
     *
     * @return Response
     */
    public function indexAction()
    {
        $teams = $this->getDoctrine()
            ->getRepository('AppBundle:Team')
            ->findAll();

        if (!$teams) {
            throw $this->createNotFoundException(
                'No teams found'
            );
        }

        return ['teams' => $teams];
    }

    /**
     * @param $teamName
     * @Route("/team/{teamName}", name="team", requirements={
     *     "team": "[A-Za-z]+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:team:team.html.twig")
     *
     * @return Response
     */
    public function teamAction($teamName)
    {
        //29 запросов - 20мс
//        $team = $this->getDoctrine()
//            ->getRepository('AppBundle:Team')
//            ->findOneByCode($teamName);

        //3 запроса - 3,5мс
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository("AppBundle:Team")
            ->getTeamWithPlayers($teamName);

        $games = $em->getRepository("AppBundle:Game")
            ->getTeamGames($team);

        $gamesData = $em->getRepository("AppBundle:Game")
            ->getScores($games);

        return [
            'team' => $team,
            'games' => $gamesData
        ];
    }

    /**
     * @param $countryCode
     * @Route("/country/{countryCode}", name="country", requirements={
     *     "country": "[A-Za-z]+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:country:country.html.twig")
     *
     * @return Response
     */
    public function countryAction($countryCode)
    {
        $country = $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->findOneByCode($countryCode);

        return ['country' => $country];
    }

    /**
     * @param $team
     * @param $playerId
     * @Route("/team/{team}/player/{playerId}", name="player", requirements={
     *     "team": "Albania|Austria|Belgium|Croatia|CzechRepublic|England|France|Germany|Hungary|Iceland|Italy|NorthernIreland|Poland|Portugal|RepublicOfIreland|Romania|Russia|Slovakia|Spain|Sweden|Switzerland|Turkey|Ukraine|Wales",
     *     "playerId": "\d+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:player:player.html.twig")
     *
     * @return Response
     */
    public function playerAction($team, $playerId)
    {
        $player = $this->getDoctrine()
            ->getRepository('AppBundle:Player')
            ->find($playerId);

        return ['player' => $player];
    }

}
