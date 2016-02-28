<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Player;
use AppBundle\Form\Type\PlayerType;
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
class PlayerController extends Controller
{
     /**
     * @param $teamCode
     * @Route("/{teamCode}/squad", name="adminSquad", requirements={
     *     "teamCode": "[A-Za-z]+"
     *     })
     * @Template("AppBundle:admin:squad.html.twig")
     * @Method("GET")
     *
     * @return Response
     */
    public function squadAction($teamCode)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository("AppBundle:Team")
            ->getTeamWithPlayers($teamCode);

        return [
            'team' => $team,
        ];
    }

    /**
     * @param Request $request
     * @Route("/player/new", name="playerNew")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:player.html.twig")
     *
     * @return Response
     */
    public function newPlayerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player, [
                'em' => $em,
                'action' => $this->generateUrl('playerNew'),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($player);
                $em->flush();

                $teamCode = $player->getTeam()->getCode();
                return $this->redirectToRoute('adminSquad', ['teamCode' => $teamCode]);
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $playerId
     * @param Request $request
     * @Route("/player/{playerId}/edit", name="playerEdit", requirements={
     *     "playerId": "\d+"
     *     })
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:player.html.twig")
     *
     * @return Response
     */
    public function editPlayerAction($playerId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository('AppBundle:Player')
            ->find($playerId);

        $form = $this->createForm(PlayerType::class, $player, [
                'em' => $em,
                'action' => $this->generateUrl('playerEdit', ['playerId' => $playerId]),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                $teamCode = $player->getTeam()->getCode();
                return $this->redirectToRoute('adminSquad', ['teamCode' => $teamCode]);
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $playerId
     * @Route("/player/{playerId}/remove", name="playerRemove", requirements={
     *     "playerId": "\d+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:admin/form:player.html.twig")
     *
     * @return Response
     */
    public function removePlayerAction($playerId)
    {
        $em = $this->getDoctrine()->getManager();
        $player = $em->getRepository('AppBundle:Player')
            ->find($playerId);

        $em->remove($player);
        $em->flush();

        $teamCode = $player->getTeam()->getCode();
        return $this->redirectToRoute('adminSquad', ['teamCode' => $teamCode]);
    }

}