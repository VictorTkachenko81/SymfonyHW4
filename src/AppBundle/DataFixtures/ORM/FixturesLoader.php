<?php

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;

class FixturesLoader extends AbstractLoader
{

    public function getFixtures()
    {
        $kernel = $GLOBALS['kernel'];
        $env = $kernel->getEnvironment();
//        $env = $GLOBALS['env'];

        echo "\nEnvironment is: " . $env . "!!!\n\n";

        if ($env == 'test') {
            return [
                __DIR__ . '/test/countries.yml',
                __DIR__ . '/test/teams.yml',
                __DIR__ . '/test/players.yml',
                __DIR__ . '/test/games.yml',
                __DIR__ . '/test/scores.yml',
            ];
        }
        return [
            __DIR__ . '/dev/countries.yml',
            __DIR__ . '/dev/teams.yml',
            __DIR__ . '/dev/players.yml',
            __DIR__ . '/dev/games.yml',
            __DIR__ . '/dev/scores.yml',
        ];
    }

    public function countryName($count)
    {
        $names = [
                'Ukraine',
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
                'Wales',
        ];

        return $names[$count-1];
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

    public function side($count)
    {
        $side = [
            'host',
            'guest',
        ];

        return $side[$count&1 ? 0 : 1];
    }

    /**
     * @param $count
     * @param $step
     * @return int  $newCount+1 every $step
     */
    public function count($count, $step)
    {
        $newCount = ceil($count/$step);

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
        $newCount = $count&1 ?
            ($unique&1 ?
                $unique :
                $unique - 1) :
            ($unique&1 ? (
                $unique < $max ?
                $unique + 1 : $unique - 1) :
                $unique);

        return $newCount;
    }
}