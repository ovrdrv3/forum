<?php

namespace Tests\Feature;

use Tests\TestCase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CreateThreadsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;
    public function test_guests_may_not_create_or_view_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')   
            ->assertRedirect('/login');

        $this->post('/threads')   
            ->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_create_forum_threads()
    {
     	// Given we have an authenticated user
     	$this->signIn();
     	// When we hit the endpoint to create a new thread
     	$thread = make('App\Thread');
     	$response = $this->post('/threads', $thread->toArray());

        // dd($response->headers->get('Location'));
     	// Then we visit the thread page
     	$this->get($response->headers->get('Location'))
     		->assertSee($thread->title)
     		->assertSee($thread->body);
    }
    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }
    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }
    public function test_a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
