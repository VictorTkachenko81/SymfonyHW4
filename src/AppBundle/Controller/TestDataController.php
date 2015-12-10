<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Player;
use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestDataController extends Controller
{

    private $dataCountry
        = array(
            'Albania'           => array(
                'name'  => 'Albania',
                'title' => 'Albania - ALB',
            ),
            'Austria'           => array(
                'name'  => 'Austria',
                'title' => 'Austria - AUT',
            ),
            'Croatia'           => array(
                'name'  => 'Croatia',
                'title' => 'Croatia - CRO',
            ),
            'CzechRepublic'     => array(
                'name'  => 'Czech Republic',
                'title' => 'Czech Republic - CZE',
            ),
            'England'           => array(
                'name'  => 'England',
                'title' => 'England - ENG',
            ),
            'France'            => array(
                'name'  => 'France',
                'title' => 'France - FRA',
            ),
            'Germany'           => array(
                'name'  => 'Germany',
                'title' => 'Germany - GER',
            ),
            'Hungary'           => array(
                'name'  => 'Hungary',
                'title' => 'Hungary - HUN',
            ),
            'Iceland'           => array(
                'name'  => 'Iceland',
                'title' => 'Iceland - ISL',
            ),
            'Italy'             => array(
                'name'  => 'Italy',
                'title' => 'Italy - ITA',
            ),
            'NorthernIreland'   => array(
                'name'  => 'Northern Ireland',
                'title' => 'Northern Ireland - NIR',
            ),
            'Poland'            => array(
                'name'  => 'Poland',
                'title' => 'Poland - POL',
            ),
            'Portugal'          => array(
                'name'  => 'Portugal',
                'title' => 'Portugal - POR',
            ),
            'RepublicOfIreland' => array(
                'name'  => 'Republic of Ireland',
                'title' => 'Republic of Ireland - IRL',
            ),
            'Romania'           => array(
                'name'  => 'Romania',
                'title' => 'Romania - ROU',
            ),
            'Russia'            => array(
                'name'  => 'Russia',
                'title' => 'Russia - RUS',
            ),
            'Slovakia'          => array(
                'name'  => 'Slovakia',
                'title' => 'Slovakia - SVK',
            ),
            'Spain'             => array(
                'name'  => 'Spain',
                'title' => 'Spain - ESP',
            ),
            'Sweden'            => array(
                'name'  => 'Sweden',
                'title' => 'Sweden - SWE',
            ),
            'Switzerland'       => array(
                'name'  => 'Switzerland',
                'title' => 'Switzerland - SUI',
            ),
            'Turkey'            => array(
                'name'  => 'Turkey',
                'title' => 'Turkey - TUR',
            ),
            'Ukraine'           => array(
                'name'  => 'Ukraine',
                'title' => 'Ukraine - UKR',
            ),
            'Wales'             => array(
                'name'  => 'Wales',
                'title' => 'Wales - WAL',
            ),
        );


    /**
     * @Route("/test_data_add", name="testDataAdd")
     *
     * @return Response
     */
    public function createAction()
    {
        $faker = \Faker\Factory::create();

        foreach ($this->dataCountry as $key => $dataCollection) {

            $em = $this->getDoctrine()->getManager();

            $team = new Team();
            $team->setCode($key);
            $team->setName($dataCollection['name']);
            $team->setLogo('/images/' . $key . '.png');
            $team->setDescription($faker->text($maxNbChars = 300));

            $country = new Country();
            $country->setName($dataCollection['name']);
            $country->setCode($key);
            $country->setPhoto($faker->imageUrl($width = 400, $height = 400, 'city'));
            $country->setDescription($faker->text($maxNbChars = 400));

            $team->setCountry($country);

            $em->persist($team);
            $em->persist($country);

            for ($i = 0; $i <= 5; $i++) {
                $player = new Player();
                $player->setFirstName($faker->firstNameMale);
                $player->setLastName($faker->lastName);
                $player->setPosition($faker->word);
                $player->setStatistic($faker->randomDigit);
                $player->setAge($faker->dateTimeBetween(
                    $startDate = '-35 years',
                    $endDate = '-20 years'
                ));
                $player->setBiography($faker->realText($maxNbChars = 200, $indexSize = 2));
                $player->setPhoto($faker->imageUrl($width = 400, $height = 400, 'people'));
                $player->setTeam($team);

                $em->persist($player);
            }

            $em->flush();

        }

        return $this->redirectToRoute('homepage');
    }
}