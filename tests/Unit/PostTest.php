<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Post;
use Carbon\Carbon;

class PostTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_post()
    {
        $post = Post::create([
            'title' => 'Test Post',
            'date' => Carbon::createFromFormat('d-m-Y', '28-06-2023')->format('Y-m-d'),
            'description' => 'This is a test post.',
        ]);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('2023-06-28', $post->date);
        $this->assertEquals('This is a test post.', $post->description);
    }

    #[Test]
    public function it_can_update_a_post()
    {
        $post = Post::factory()->create();

        $post->update([
            'title' => 'Updated Title',
            'date' => Carbon::createFromFormat('d-m-Y', '30-06-2023')->format('Y-m-d'),
            'description' => 'Updated description.',
        ]);

        $this->assertEquals('Updated Title', $post->title);
        $this->assertEquals('2023-06-30', $post->date);
        $this->assertEquals('Updated description.', $post->description);
    }

    #[Test]
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create();

        $post->delete();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
