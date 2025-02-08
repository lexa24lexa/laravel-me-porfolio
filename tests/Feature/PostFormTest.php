<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Post;
use Carbon\Carbon;

class PostFormTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_stores_a_new_post()
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'A valid title',
            'date' => Carbon::createFromFormat('d-m-Y', '01-01-2020')->format('Y-m-d'),
            'description' => 'A valid description'
        ]);

        $response->assertRedirect(route('work'));

        $this->assertDatabaseHas('posts', [
            'title' => 'A valid title',
            'date' => '2020-01-01',
            'description' => 'A valid description'
        ]);
    }

    #[Test]
    public function it_updates_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->put(route('posts.update', $post->id), [
            'title' => 'An updated title',
            'date' => Carbon::createFromFormat('d-m-Y', '02-02-2021')->format('Y-m-d'),
            'description' => 'An updated description'
        ]);

        $response->assertRedirect(route('work'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'An updated title',
            'date' => '2021-02-02',
            'description' => 'An updated description'
        ]);
    }

    #[Test]
    public function it_deletes_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', $post->id));

        $response->assertRedirect(route('work'));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }
}

