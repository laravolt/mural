<?php
namespace Laravolt\Mural\Test;

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Mural\Comment;

class TestCase extends \Orchestra\Testbench\TestCase
{

	public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Laravolt\Mural\ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Mural' => \Laravolt\Mural\Facade::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => __DIR__.'/database.sqlite',
            'prefix' => '',
        ]);
        $app['config']->set('mural.default_commentable', DummyPost::class);
        $app['config']->set('mural.default_commentator', DummyUser::class);
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


        $post = DummyPost::create(['content' => 'My first post']);
        $user = DummyUser::create(['name' => 'Heru']);

        for($i = 1; $i <= 2; $i++) {
            Comment::create([
                'author_id' => $user->id,
                'commentable_id' => $post->id,
                'commentable_type' => DummyPost::class,
                'body' => 'My Comment ' . $i,
                'room' => 'test-room'
            ]);
        }
    }
}