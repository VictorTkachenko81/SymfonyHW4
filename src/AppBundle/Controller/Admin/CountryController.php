<?php
/**
 * @link http://webmozart.io
 * pattern fabrica
 */
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Country;
use AppBundle\Form\Type\CountryType;
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
class CountryController extends Controller
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
     * @param Request $request
     * @Route("/country/new", name="countryNew")
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:country.html.twig")
     *
     * @return Response
     */
    public function newCountryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $country = new Country();

        $form = $this->createForm(CountryType::class, $country, [
                'em' => $em,
                'action' => $this->generateUrl('countryNew'),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

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
     * @param $countryCode
     * @param Request $request
     * @Route("/country/{countryCode}/edit", name="countryEdit", requirements={
     *     "countryCode": "[A-Za-z]+"
     *     })
     * @Method({"GET", "POST"})
     * @Template("AppBundle:admin/form:country.html.twig")
     *
     * @return Response
     */
    public function editCountryAction($countryCode, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('AppBundle:Country')
            ->findOneByCode($countryCode);

        $form = $this->createForm(CountryType::class, $country, [
                'em' => $em,
                'action' => $this->generateUrl('countryEdit', ['countryCode' => $countryCode]),
                'method' => Request::METHOD_POST,
            ])
            ->add('save', SubmitType::class, array('label' => 'Save'));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
//                $em->persist($country);
                $em->flush();

                return $this->redirectToRoute('adminCountry');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param $countryCode
     * @Route("/country/{countryCode}/remove", name="countryRemove", requirements={
     *     "countryCode": "[A-Za-z]+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:admin/form:country.html.twig")
     *
     * @return Response
     */
    public function removeCountryAction($countryCode)
    {
        $em = $this->getDoctrine()->getManager();
        $country = $em->getRepository('AppBundle:Country')
            ->findOneByCode($countryCode);

        $em->remove($country);
        $em->flush();

        return $this->redirectToRoute('adminCountry');
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