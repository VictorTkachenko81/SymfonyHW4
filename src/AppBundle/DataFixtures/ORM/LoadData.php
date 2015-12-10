<?php
/**
 * Created by PhpStorm.
 * User: victor
 * Date: 07.12.15
 * Time: 10:53
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Team;
use AppBundle\Entity\Country;
use AppBundle\Entity\Player;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadData implements FixtureInterface
{

    private $dataCountry
        = array(
            'Ukraine' => array(
                'name'  => 'Ukraine',
                'title' => 'Ukraine - UKR',
            )
        );

    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create();

        foreach ($this->dataCountry as $key => $dataCollection) {

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

            $manager->persist($team);
            $manager->persist($country);

            for ($i = 0; $i <= 1; $i++) {
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

                $manager->persist($player);
            }

            $manager->flush();

        }
    }

}