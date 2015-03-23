<?php

/*
 * This file is part of Laravel Algolia.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vinkla\Tests\Algolia;

use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Mockery;
use Vinkla\Algolia\AlgoliaManager;

/**
 * This is the Algolia manager test class.
 *
 * @author Vincent Klaiber <hello@vinkla.com>
 */
class AlgoliaManagerTest extends AbstractTestBenchTestCase
{
    public function testCreateConnection()
    {
        $config = ['path' => __DIR__];

        $manager = $this->getManager($config);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('algolia.default')->andReturn('algolia');

        $this->assertSame([], $manager->getConnections());

        $return = $manager->connection();

        $this->assertInstanceOf('AlgoliaSearch\Client', $return);

        $this->assertArrayHasKey('algolia', $manager->getConnections());
    }

    protected function getManager(array $config)
    {
        $repository = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $factory = Mockery::mock('Vinkla\Algolia\Factories\AlgoliaFactory');

        $manager = new AlgoliaManager($repository, $factory);

        $manager->getConfig()->shouldReceive('get')->once()
            ->with('algolia.connections')->andReturn(['algolia' => $config]);

        $config['name'] = 'algolia';

        $manager->getFactory()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock('AlgoliaSearch\Client'));

        return $manager;
    }
}
