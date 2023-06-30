<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiPostTest extends TestCase
{
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Membuat pengguna pertama (user pertama) dalam database aktif
        $this->user = User::first();

        // Mengatur pengguna saat ini menjadi pengguna pertama
        Passport::actingAs($this->user);
         
        // Membuat token bearer token untuk pengguna pertama
        $this->token = $this->user->createToken('TestToken')->accessToken;
    }

    /**
     * Test retrieving a list of posts via API.
     *
     * @return void
    */
    public function test_list_all_posts_api(): void
    {
        // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];

        // Mengakses rute untuk mendapatkan daftar posts
        $response = $this->get('/api/v1.0/posts', $headers);

        // Menyakinkan bahwa respons memiliki status 200 (sukses)
        $response->assertStatus(200);
    }

    /**
     * Test retrieving a detail of post via API.
     *
     * @return void
    */
    public function test_show_detail_post_api(): void
    {
        $post = Post::first();
        
        // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];

        // Mengakses rute untuk mendapatkan detail post
        $response = $this->get('/api/v1.0/posts/detail/' . $post->id, $headers);

        // Menyakinkan bahwa respons memiliki status 200 (sukses)
        $response->assertStatus(200)
                ->assertSee($post->title)
                ->assertSee($post->content);
    }

    /**
     * Test creating a new post via API.
     *
     * @return void
    */
    public function test_create_new_post_api(): void 
    {
        // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];

        Storage::fake('public'); // Use the 'public' disk for testing file uploads
        $image = UploadedFile::fake()->image('test-image.jpg'); // Create a fake image file
        
        // Mengakses rute untuk mendapatkan detail post
        $response = $this->post('/api/v1.0/posts', [
            'title' => 'Test Fifth Post',
            'content' => 'Test Fifth Content of Post',
            'category_id' => 3,
            'user_id' => $this->user,
            'image' => $image
        ], $headers);

        $response->assertStatus(200); 
    }

    /**
     * Test updating a selected post (with image) via API.
     *
     * @return void
    */
    public function test_update_data_post_api(): void
    {
        // I try to get post with current id post from database that will be updated
        $post = Post::findOrFail(2); 
        
         // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];

        Storage::fake('public'); // Use the 'public' disk for testing file uploads
        $image = UploadedFile::fake()->image('update-image.jpg'); // Create a fake image file
        
        // Mengakses rute untuk mendapatkan detail post
        $response = $this->put('/api/v1.0/posts/update/' . $post->id, [
            'title' => 'Test Second Post',
            'content' => 'Test Second Content of Post',
            'category_id' => 2,
            'image' => $image
        ], $headers);

        $response->assertStatus(200); 
    }
    
    /**
     * Test updating a selected post (without image) via API.
     *
     * @return void
    */
    public function test_update_data_post_without_update_image_api(): void
    {
        // I try to get post with current id from database that will be updated without update image  
        $post = Post::findOrFail(3); 
        
         // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
        
        // Mengakses rute untuk mendapatkan detail post
        $response = $this->put('/api/v1.0/posts/update/' . $post->id, [
            'title' => 'Test Third Post Updated Without Image',
            'content' => 'Third Content of Post Updated',
            'category_id' => 2
        ], $headers);

        $response->assertStatus(200); 
    }

    /**
     * Test deleting a selected post via API.
     *
     * @return void
    */
    public function test_delete_post_api(): void 
    {
        // I use soft delete, so data won't be deleted permanently
        // I wanna delete post with current id from database 
        $post = Post::findOrFail(1); 
        
         // Mengatur header Authorization dengan bearer token
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
        
        // Mengakses rute untuk mendapatkan detail post
        $response = $this->delete('/api/v1.0/posts/delete/' . $post->id, $headers);

        $response->assertStatus(200); 
    }
}