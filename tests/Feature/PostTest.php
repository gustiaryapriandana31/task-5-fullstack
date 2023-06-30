<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    /**
     * make function setUp() to login user
    */ 
    protected function setUp() : void
    {
        parent::setUp();
        $user = User::first();
        
        $this->actingAs($user);
    }

    /**
     * A basic feature test to display list all posts.
    */
    public function test_list_all_posts(): void
    {
        $response = $this->get('/posts');

        $response->assertStatus(200)
                ->assertViewIs('posts.index');
    }

    /**
     * A basic feature test to show a detail post.
    */
    public function test_show_detail_post(): void
    {
        $post = Post::first();
        
        $response = $this->get('/posts/detail/' . $post->id);

        $response->assertStatus(200)
                ->assertViewIs('posts.show')
                ->assertViewHas('post', $post)
                ->assertSee($post->title)
                ->assertSee($post->content);
    }

    /**
     * A basic feature test to display create form post.
    */
    public function test_get_form_create_post(): void
    {
        $response = $this->get('/posts/create');

        $response->assertStatus(200)
                ->assertViewIs('posts.create');
    }

    /**
     * A basic feature test to create new post.
    */
    public function test_create_new_post(): void
    {
        
        Storage::fake('public'); // Use the 'public' disk for testing file uploads
        $image = UploadedFile::fake()->image('test-image.jpg'); // Create a fake image file

        $response = $this->post('/posts', [
            'title' => 'Test First Post',
            'content' => 'Test First Content of Post',
            'category_id' => 1,
            'user_id' => 1,
            'image' => $image
        ]);

        $response->assertStatus(302); // use http status 302 because after create new post, it will redirect to /posts
        $response->assertRedirect('/posts');
    }

    /**
     * A basic feature test to display edit form post.
    */
    public function test_get_form_edit_post(): void
    {
        $post = Post::first();
        
        $response = $this->get('/posts/edit/' . $post->id);

        $response->assertStatus(200)
                ->assertViewIs('posts.edit')
                ->assertViewHas('post', $post);
    }
    
    /**
     * A basic feature test to update a post (with image).
    */
    public function test_update_data_post(): void
    {
        // I try to get post with current id from database that will be updated
        $post = Post::findOrFail(4); 

        Storage::fake('public'); 
        $image = UploadedFile::fake()->image('update-image.jpg'); 

        $response = $this->put('/posts/update/' . $post->id, [
            'title' => 'Update Title for several time',
            'content' => 'Update Content Again',
            'category_id' => 2,
            'image' => $image
        ]);

        $response->assertStatus(302); // use http status 302 because after update a post, it will redirect to /posts
        $response->assertRedirect('/posts');
    }

    /**
     * A basic feature test to update a post (without image).
    */
    public function test_update_data_post_without_update_image() : void 
    {
        // I try to get post with current id post from database that will be updated without update image
        $post = Post::findOrFail(5); 

        $response = $this->put('/posts/update/' . $post->id, [
            'title' => 'Update Third Title Again ',
            'content' => 'Update Third Content Again',
            'category_id' => 2,
        ]);

        $response->assertStatus(302); // use http status 302 because after update a post, it will redirect to /posts
        $response->assertRedirect('/posts');
    }

    /**
     * A basic feature test to delete a post.
    */
    public function test_delete_post() : void 
    {
        // I use soft delete, so data won't be deleted permanently
        // I wanna delete post with current id from database 
        $post = Post::findOrFail(5);

        $response = $this->delete('/posts/delete/' . $post->id);

        $response->assertStatus(302); // use http status 302 because after delete a post, it will redirect to /posts
        $response->assertRedirect('/posts');
    }
}