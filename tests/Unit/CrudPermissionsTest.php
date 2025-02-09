<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CrudPermissionsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_edit_and_delete_posts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Criar post
        $postData = [
            'title' => 'Admin Post',
            'description' => 'This is an admin-created post.',
            'date' => now()->toDateString(),
        ];
        $response = $this->postJson('/work', $postData);
        $response->assertStatus(201);

        $post = Post::latest()->first(); // Garante que temos o último post criado
        $this->assertNotNull($post);

        // Atualizar post
        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated content',
            'date' => now()->toDateString(),
        ];
        $response = $this->putJson("/work/{$post->id}", $updatedData);

        dump($response->json());

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);

        $response = $this->deleteJson("/work/{$post->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['title' => 'Updated Title']);
    }

    #[Test]
    public function normal_user_cannot_create_posts(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $postData = [
            'title' => 'User Post',
            'description' => 'This should not be allowed.',
            'date' => now()->toDateString(),
        ];
        $response = $this->postJson('/work', $postData);

        $response->assertStatus(403);
    }

    #[Test]
    public function normal_user_cannot_edit_posts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $post = Post::factory()->create(['user_id' => $admin->id]);

        $this->actingAs($user);

        $updatedData = [
            'title' => 'Unauthorized Edit',
            'description' => 'Should not be allowed',
            'date' => now()->toDateString(),
        ];
        $response = $this->putJson("/work/{$post->id}", $updatedData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('posts', ['title' => 'Unauthorized Edit']);
    }

    #[Test]
    public function normal_user_cannot_delete_posts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $post = Post::factory()->create(['user_id' => $admin->id]);

        $this->actingAs($user);

        $response = $this->deleteJson("/work/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
