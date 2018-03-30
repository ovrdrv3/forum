<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;

    public function setUp(){
    	parent::setUp();
    	$this->thread = factory('App\Thread')->create();
    }

    public function test_a_thread_has_a_user()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);    
    }

    public function test_a_thread_can_have_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);    
    }    

    public function test_a_user_can_view_all_threads()
    {
        $this->get('/threads')
        	->assertSee($this->thread->title);
    }

    public function test_a_user_can_browse_threads()
    {
		$this->get($this->thread->path())
        	->assertSee($this->thread->title);
    }
    function test_a_user_can_read_replies_that_are_associated_with_a_thread(){
    	//Given we have a thread
    	$reply = factory('App\Reply')
    		->create(['thread_id' => $this->thread->id]);

    	$this->get($this->thread->path())
    		->assertSee($reply->body);
    	// When we visit a thread page
    	// We should see replies
    }

    public function test_a_user_can_filter_threads_associated_with_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread',['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_user_can_fiter_threads_according_to_a_tag()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        // This one isnt going to be a part of channel above.
        $threadNotInChannel = create('App\Thread');
        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title );
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'FooBar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }
    public function test_a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');
        // dd($thread->path());
        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }
    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'John']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('/threads?by=John')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);

    }
}
