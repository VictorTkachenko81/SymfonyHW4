<?php
/**
 * @link http://webmozart.io
 * pattern fabrica
 * ToDo edited form data doesn't save
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\CountryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
     /**
     * @Route("/country", name="adminCountry")
     * @Template("AppBundle:admin:country.html.twig")
     * @Method("GET")
     *
     * @return Response
     */
    public function countryAction()
    {
        $countries = $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->findAll();

        return [
            'countries' => $countries,
        ];
    }

    /**
     * @param $countryCode
     * @param $action
     * @param Request $request
     * @Route("/country/new", name="countryNew", defaults={"countryCode": "new", "action": "new"})
     * @Route("/country/{countryCode}/{action}", name="countryEdit", requirements={
     *     "countryCode": "[A-Za-z]+",
     *     "action": "new|edit|remove"
     *     })
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:country.html.twig")
     *
     * @return Response
     */
    public function editCountryAction($countryCode, $action, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($action == "new") $country = new Country();
        else {
            $country = $em->getRepository('AppBundle:Country')
                ->findOneByCode($countryCode);
        }

        if ($action == "remove") {
            $em->remove($country);
            $em->flush();

            return $this->redirectToRoute('adminCountry');
        }

        $form = $this->createForm(CountryType::class, $country, [
            'em' => $em,
            'action' => $this->generateUrl('countryEdit', ['countryCode' => $countryCode, 'action' => $action]),
            'method' => Request::METHOD_POST,
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($country);
                $em->flush();

                return $this->redirectToRoute('adminCountry');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/success", name="adminSuccess")
     * @Template("AppBundle:admin:success.html.twig")
     *
     * @return Response
     */
    public function successAction()
    {
        return [];
    }
}