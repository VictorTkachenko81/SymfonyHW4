<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameScore;
use AppBundle\Form\Type\GameType;
use AppBundle\Model\PaginatorWithPages;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class GameController extends Controller
{

    /**
     * @Route("/games", name="adminGames")
     * @Template("AppBundle:admin:games.html.twig")
     *
     * @return Response
     */
    public function gamesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository("AppBundle:Game")
            ->getAllGamesWithDep();

        $paginator = new PaginatorWithPages($games, $fetchJoinCollection = true);

        return [
            'games' => $paginator,
        ];
    }


    /**
     * @param Request $request
     * @Route("/game/new", name="gameNew")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:game.html.twig")
     *
     * @link http://symfony.com/doc/2.8/cookbook/form/form_collections.html
     *
     * @return Response
     */
    public function newGameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $game = new Game();
//        $game->setGamedate();
        $score['host'] = new GameScore();
        $score['guest'] = new GameScore();
        $game->addScore($score['host']->setSide('host'));
        $game->addScore($score['guest']->setSide('guest'));

        $form = $this->createForm(GameType::class, $game, [
                'em'     => $em,
                'action' => $this->generateUrl('gameNew'),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($game);
                $em->flush();

                return $this->redirectToRoute('adminGames');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

}