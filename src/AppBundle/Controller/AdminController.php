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
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function createCountryAction(Request $request)
    {
        $countries = $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->findAll();

        $country = new Country();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CountryType::class, $country, [
            'em' => $em,
            'action' => $this->generateUrl('adminCountry'),
            'method' => Request::METHOD_POST,
        ]);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($country);
                $em->flush();

                return $this->redirectToRoute('adminSuccess');
            }
        }

        return [
            'countries' => $countries,
            'form'      => $form->createView(),
        ];
    }


    /**
     * @param $countryCode
     * @Route("/country/{countryCode}", name="countryEdit", requirements={
     *     "country": "[A-Za-z]+"
     *     })
     * @Method("GET")
     * @Template("AppBundle:admin/form:country.html.twig")
     *
     * @return Response
     */
    public function editCountryAction($countryCode)
    {
        $country = $this->getDoctrine()
            ->getRepository('AppBundle:Country')
            ->findOneByCode($countryCode);

//        $em = $this->getDoctrine()->getManager();
//        $country = $em->getRepository('AppBundle:Country')->find($id);
//        $em->flush();

        if (!$country) {
            throw $this->createNotFoundException(
                'No country found'
            );
        }
        else {
            $em = $this->getDoctrine()->getManager();

            $form = $this->createForm(CountryType::class, $country, [
                'em' => $em,
                'action' => $this->generateUrl('adminCountry'),
                'method' => Request::METHOD_POST,
            ]);

            return [
                'form' => $form->createView(),
            ];
        }

    }

    /**
     * @Route("/country/success", name="adminSuccess")
     * @Template("AppBundle:admin:success.html.twig")
     *
     * @return Response
     */
    public function successAction()
    {
        return [];
    }
}