<?php
namespace Laravolt\Mural\Test;

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Mural\Comment;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * setUp() akan dipanggil setiap method test case.
     * Jadi setiap test case akan memiliki fresh data.
     */
	public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    /**
     * Fungsinya mirip seperti config/app.php bagian registering Service Provider di Laravel apps
     */
    protected function getPackageProviders($app)
    {
        return [
            \Laravolt\Mural\ServiceProvider::class,
        ];
    }

    /**
     * Fungsinya mirip seperti config/app.php bagian registering Facade di Laravel apps
     */
    protected function getPackageAliases($app)
    {
        return [
            'Mural' => \Laravolt\Mural\Facade::class,
        ];
    }

    /**
     * Setup-setup config disini.
     * Package Mural memiliki dependency pada config/mural.php, namun phpunit tidak otomatis mendeteksi config tersebut.
     * Maka dari itu kita perlu ngeset manual setiap config yang diperlukan package kita untuk berjalan.
     *
     * Database menggunakan database.sqlite file yang akan digenerate di folder /tests.
     */
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

    /**
     * Setup database untuk dummy data yang dibutuhkan untuk testing.
     * Method ini akan dijalankan setiap test case.
     *
     * File database.sqlite akan dinull kan sehingga test case akan memiliki fresh data setiap dijalankan.
     *
     * Penggunaan package Mural bergantung pada Model di Laravel apps seperti kewajiban implement interface di model atau
     * penggunaan trait.
     * Maka kita perlu membuat dummy model (DummyPost, DummyUser) agar dapat mensimulasikan situasi tersebut, beserta table-tablenya.
     * Disini dilakukan schema creation untuk dummy-dummy model tersebut.
     *
     * Package Mural membutuhkan table db tersendiri (table Comment), maka kita perlu memigratekannya juga.
     */
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