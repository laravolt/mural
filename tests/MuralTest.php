<?php

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Mural\Comment;
use Laravolt\Mural\Test\DummyPost;
use Laravolt\Mural\Test\DummyUser;
use Symfony\Component\DomCrawler\Crawler;

class MuralTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            Laravolt\Mural\ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Mural' => Laravolt\Mural\Facade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => __DIR__.'/database.sqlite',
            'prefix' => '',
        ]);
        $app['config']->set('mural.default_commentable', Laravolt\Mural\Test\DummyPost::class);
        $app['config']->set('mural.default_commentator', Laravolt\Mural\Test\DummyUser::class);
        $app['config']->set('mural.skin', 'semantic-ui');
    }

    protected function setUpDatabase()
    {
        file_put_contents(__DIR__.'/database.sqlite', null);

        $this->app['db']->connection()->getSchemaBuilder()->create('dummy_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('dummy_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $this->artisan('migrate', [
            '--database' => 'sqlite',
            '--realpath' => realpath(__DIR__.'/../database/migrations')
        ]);

        for ($i = 1; $i <= 10; ++$i) {
            DummyPost::create(['content' => $i]);
        }

        DummyUser::create(['name' => 'Heru']);
    }

    public function test_add_new_comment()
    {
        Auth::shouldReceive('user')
            ->once()
            ->andReturn(DummyUser::find(1));

        $post = DummyPost::find(1);
        Mural::addComment($post, 'My Comment', 'test-room');

        $this->seeInDatabase('comments', ['body' => 'My Comment']);

        $comments = Mural::getComments($post, 'test-room');
    }

    public function test_if_render_is_showing()
    {
        // Populate data first
        $post = DummyPost::find(1);

        for($i = 1; $i <= 2; $i++) {
            Comment::create([
                'author_id' => DummyUser::where(['name' => 'Heru'])->first()->id,
                'commentable_id' => $post->id,
                'commentable_type' => DummyPost::class,
                'body' => 'My Comment ' . $i,
                'room' => 'test-room'
            ]);
        }

        $html = Mural::render($post, 'test-room');
        $crawler = new Crawler($html);

        $this->assertEquals($crawler->filter('div[data-type = "Laravolt\Mural\Test\DummyPost"]')->count(), 1);
        $this->assertEquals($crawler->filter('div[data-room = "test-room"]')->count(), 1);
        $this->assertContains($crawler->filter('.author')->text(), 'Heru');
        $this->assertContains('ago', $crawler->filter('.date')->text());

        $this->assertContains('My Comment 2', $crawler->filter('.content .text')->eq(0)->text());
        $this->assertContains('My Comment 1', $crawler->filter('.content .text')->eq(1)->text());
    }

    public function test_get_comments()
    {
        // Populate data first
        $post = DummyPost::find(1);

        for($i = 1; $i <= 2; $i++) {
            Comment::create([
                'author_id' => DummyUser::where(['name' => 'Heru'])->first()->id,
                'commentable_id' => $post->id,
                'commentable_type' => DummyPost::class,
                'body' => 'My Comment ' . $i,
                'room' => 'test-room'
            ]);
        }

        $post = DummyPost::find(1);
        $comments = Mural::getComments($post, 'test-room');

        $this->assertEquals($comments->count(), 2);
    }
}
