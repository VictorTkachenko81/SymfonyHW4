<?php
/**
 * https://github.com/nelmio/alice#table-of-contents
 * https://github.com/nelmio/alice/blob/master/doc/customizing-data-generation.md#custom-faker-data-providers
 * https://github.com/Etheriq/Alice/blob/ShowResult/src/AppBundle/DataFixtures/ORM/FixturesLoader.php
 * в setUp
    $this->runCommand(['command' => 'doctrine:schema:update', '--force' => true]);
    $this->runCommand(['command' => 'hautelook_alice:fixtures:load', '--no-interaction' => true]);
    в tearDown
    $this->runCommand(['command' => 'doctrine:schema:drop', '--force' => true]);
 */

namespace AppBundle\DataFixtures\ORM;

use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;

class FixturesLoader extends AbstractLoader
{
    /**
     * Returns an array of file paths to fixtures
     *
     * @return array<string>
     */
    public function getFixtures()
    {
//        $env = $this->container->get('kernel')->getEnvironment();
//        if ($env == 'test') {
//            return [
//                __DIR__ . '/DataForTests/tags.yml',
//                __DIR__ . '/DataForTests/categories.yml',
//                __DIR__ . '/DataForTests/users.yml',
//            ];
//        }
        return [
            __DIR__ . '/Data/countries.yml',
            __DIR__ . '/Data/teams.yml',
//            __DIR__ . '/Data/players.yml',
            __DIR__ . '/Data/games.yml',
        ];
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
}