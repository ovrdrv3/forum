<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

	function test_unauthenticated_users_may_not_add_replies()
	{
        $this->withExceptionHandling();
        
		$this->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
	}
	function test_an_authenticated_user_may_participate_in_forum_threads()
	{
		// Given we have an authenticated user  
		$this->signIn();
		// an an exsisting thread
		$thread = factory('App\Thread')->create();
		// when the user adds a reply to the thread
		$reply 	= factory('App\Reply')->make();
		$this->post($thread->path() . '/replies', $reply->toArray());
		// Then their reply should be visible on the page.	
		$this->get($thread->path())
			->assertSee($reply->body);
	}
	public function test_a_reply_must_contain_a_body()
	{
		$this->withExceptionHandling()->signIn();

		$thread = create('App\Thread');
		// when the user adds a reply to the thread
		$reply 	= make('App\Reply', ['body' => null]);
		$this->post($thread->path() . '/replies', $reply->toArray())
			->assertSessionHasErrors('body'); 		
		
	}
}
