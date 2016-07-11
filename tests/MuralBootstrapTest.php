<?php
namespace Laravolt\Mural\Test;

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Mural\Comment;
use Laravolt\Mural\Test\DummyPost;
use Laravolt\Mural\Test\DummyUser;
use Symfony\Component\DomCrawler\Crawler;

class MuralBootstrapTest extends TestCase
{
	protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('mural.skin', 'bootstrap');
    }

	public function test_render_if_showing()
	{
		$post = DummyPost::find(1);
        $html = \Mural::render($post, 'test-room');
        $crawler = new Crawler($html);

        $this->assertEquals($crawler->filter('div[data-type = "Laravolt\Mural\Test\DummyPost"]')->count(), 1);
        $this->assertEquals($crawler->filter('div[data-room = "test-room"]')->count(), 1);
        $this->assertContains($crawler->filter('.author')->text(), 'Heru');
        $this->assertContains('ago', $crawler->filter('.date')->text());
        $this->assertContains('My Comment 2', $crawler->filter('.media-body p')->eq(0)->text());
        $this->assertContains('My Comment 1', $crawler->filter('.media-body p')->eq(1)->text());

	}	
}
