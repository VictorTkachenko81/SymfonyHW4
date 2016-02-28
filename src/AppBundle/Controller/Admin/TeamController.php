<?php
/**
 * @link http://webmozart.io
 * pattern fabrica
 */
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Team;
use AppBundle\Form\Type\TeamType;
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
class TeamController extends Controller
{
     /**
     * @Route("/team", name="adminTeam")
     * @Template("AppBundle:admin:team.html.twig")
     * @Method("GET")
     *
     * @return Response
     */
    public function teamAction()
    {
        $teams = $this->getDoctrine()
            ->getRepository('AppBundle:Team')
            ->findAll();

        return [
            'teams' => $teams,
        ];
    }

    /**
     * @param Request $request
     * @Route("/team/new", name="teamNew")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:team.html.twig")
     *
     * @return Response
     */
    public function newTeamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team, [
                'em' => $em,
                'action' => $this->generateUrl('teamNew'),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($team);
                $em->flush();

                return $this->redirectToRoute('adminTeam');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $teamCode
     * @param Request $request
     * @Route("/team/{teamCode}/edit", name="teamEdit", requirements={
     *     "teamCode": "[A-Za-z]+"
     *     })
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:team.html.twig")
     *
     * @return Response
     */
    public function editTeamAction($teamCode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Team')
            ->findOneByCode($teamCode);

        $form = $this->createForm(TeamType::class, $team, [
                'em' => $em,
                'action' => $this->generateUrl('teamEdit', ['teamCode' => $teamCode]),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                return $this->redirectToRoute('adminTeam');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $teamCode
     * @Route("/team/{teamCode}/remove", name="teamRemove", requirements={
     *     "teamCode": "[A-Za-z]+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:admin/form:team.html.twig")
     *
     * @return Response
     */
    public function removeTeamAction($teamCode)
    {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('AppBundle:Team')
            ->findOneByCode($teamCode);

        $em->remove($team);
        $em->flush();

        return $this->redirectToRoute('adminTeam');
    }

}