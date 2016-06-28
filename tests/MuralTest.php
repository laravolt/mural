<?php
namespace Laravolt\Mural\Test;

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Mural\Comment;
use Laravolt\Mural\Test\DummyPost;
use Laravolt\Mural\Test\DummyUser;
use Symfony\Component\DomCrawler\Crawler;

class MuralTest extends TestCase
{
    public function test_add_new_comment()
    {
        \Auth::shouldReceive('user')
            ->once()
            ->andReturn(DummyUser::find(1));

        $post = DummyPost::find(1);
        \Mural::addComment($post, 'My new comment', 'test-room');

        $this->seeInDatabase('comments', ['body' => 'My new comment', 'room' => 'test-room']);
    }

    public function test_if_render_is_showing()
    {
        $post = DummyPost::find(1);
        $html = \Mural::render($post, 'test-room');
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
        $post = DummyPost::find(1);
        $comments = \Mural::getComments($post, 'test-room');

        $this->assertEquals($comments->count(), 2);
    }

    public function test_remove_comment()
    {
        \Auth::shouldReceive('user')
            ->once()
            ->andReturn(DummyUser::where(['is_admin' => 1])->first());

        $comment = Comment::where(['body' => 'My Comment 1'])->first();
        $this->assertEquals($comment->body, 'My Comment 1');

        \Mural::remove($comment->id);

        $comment = Comment::where(['body' => 'My Comment 1'])->first();
        $this->assertEquals($comment, null);
    }

    public function test_non_admin_cannot_remove_comment()
    {
        \Auth::shouldReceive('user')
            ->once()
            ->andReturn(DummyUser::where(['is_admin' => 0])->first());

        $comment = Comment::where(['body' => 'My Comment 1'])->first();
        $this->assertEquals($comment->body, 'My Comment 1');

        $result = \Mural::remove($comment->id);

        $comment = Comment::where(['body' => 'My Comment 1'])->first();
        $this->assertEquals($comment->body, 'My Comment 1');
        $this->assertEquals($result, false);
    }
}
