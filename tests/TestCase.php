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
        // Setup default database to use sqlite :memory:
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

        for ($i = 1; $i <= 10; ++$i) {
            DummyPost::create(['content' => $i]);
        }

        DummyUser::create(['name' => 'Heru']);

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
    }
}