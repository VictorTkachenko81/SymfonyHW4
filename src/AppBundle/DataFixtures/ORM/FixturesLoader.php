<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;

class FixturesLoader implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        Fixtures::load(__DIR__ . '/Data/countries.yml', $manager, ['providers' => [$this]]);
        Fixtures::load(__DIR__ . '/Data/teams.yml', $manager, ['providers' => [$this]]);
        Fixtures::load(__DIR__ . '/Data/players.yml', $manager, ['providers' => [$this]]);
        Fixtures::load(__DIR__ . '/Data/games.yml', $manager, ['providers' => [$this]]);
        Fixtures::load(__DIR__ . '/Data/scores.yml', $manager, ['providers' => [$this]]);
    }


    public function countryName()
    {
        $names = [
                'Albania',
                'Austria',
                'Croatia',
                'CzechRepublic',
                'England',
                'France',
                'Germany',
                'Hungary',
                'Iceland',
                'Italy',
                'NorthernIreland',
                'Poland',
                'Portugal',
                'RepublicOfIreland',
                'Romania',
                'Russia',
                'Slovakia',
                'Spain',
                'Sweden',
                'Switzerland',
                'Turkey',
                'Ukraine',
                'Wales',
        ];

        return $names[array_rand($names)];
    }

    public function position()
    {
        $positions = [
            'Goalkeeper',
            'Defender',
            'Midfielder',
            'Forward',
            'Coach',
        ];

        return $positions[array_rand($positions)];
    }

    /**
     * @param $count
     * @param $step
     * @return int  $newCount+1 every $step
     */
    public function count($count, $step)
    {
        $newCount = is_int($count/$step)? $count/$step : ceil($count/$step);

        return $newCount;
    }

    /**
     * @param $count
     * @param $max
     * @return int if count odd return unique odd else return unique even
     */
    public function unique($count, $max)
    {
        $faker = \Faker\Factory::create();
        $unique = $faker->numberBetween(1, $max);
        $newCount = $count&1 ? ($unique&1 ? $unique : $unique - 1) : ($unique&1 ? ($unique < $max ? $unique + 1 : $unique - 1) : $unique);

        return $newCount;
    }
}