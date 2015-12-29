<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameScore;
use AppBundle\Form\Type\GameType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class GameController extends Controller
{

    /**
     * @param Request $request
     * @Route("/games", name="adminGames")
     * @Template("AppBundle:admin:games.html.twig")
     *
     * @return Response
     */
    public function gamesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $games = $em->getRepository("AppBundle:Game")
            ->getGamesWithDep(1, 20);

        $form = $this->createFormBuilder($games)
            ->setAction($this->generateUrl('adminGames'))
            ->setMethod('POST')
            ->add('games', ChoiceType::class, array(
                    'choices' => $games,
                    'choices_as_values' => true,
                    'expanded' => true,
                    'multiple' => true,
                    'choice_value' => 'id',
                    'label' => false,
                    'choice_label' => 'id',
                )
            )
            ->add('delete', SubmitType::class, array(
                'label' => 'Remove',
                'attr' => [
                    'class' => 'btn btn-xs btn-danger'
                ],))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                foreach ($data['games'] as $game) {
                    $em->remove($game);
                }
                $em->flush();

                return $this->redirectToRoute('adminGames');
            }
        }

        return [
            'games' => $games,
            'delete' => $form->createView(),
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
        $game->setGamedate(new \DateTime('now'));
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



    /**
     * @param $id
     * @param Request $request
     * @Route("/game/{id}/edit", name="gameEdit")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:game.html.twig")
     *
     * @return Response
     */
    public function editGameAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository("AppBundle:Game")
            ->find($id);

        $form = $this->createForm(GameType::class, $game, [
            'em'     => $em,
            'action' => $this->generateUrl('gameEdit', ['id' => $id]),
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


    /**
     * @param $id
     * @Route("/game/{id}/remove", name="gameRemove", requirements={
     *     "id": "\d+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:admin/form:game.html.twig")
     *
     * @return Response
     */
    public function removeGameAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('AppBundle:Game')
            ->find($id);

        $em->remove($game);
        $em->flush();

        return $this->redirectToRoute('adminGames');
    }
}