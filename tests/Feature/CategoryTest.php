<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
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
     * A basic feature test example.
     */
    public function test_list_all_categories(): void
    {
        $response = $this->get('/categories');

        $response->assertStatus(200)
                ->assertViewIs('categories.index');
    }
    
    /**
     * A basic feature test to display create form post.
    */
    public function test_get_form_create_category(): void
    {
        $response = $this->get('/categories/create');

        $response->assertStatus(200)
                ->assertViewIs('categories.create');
    }

     /**
     * A basic feature test to create new post.
    */
    public function test_create_new_category(): void
    {
        $response = $this->post('/categories', [
            'category_name' => 'Test New Gategory',
            'user_id' => 1,
        ]);

        $response->assertStatus(302); // use http status 302 because after create new post, it will redirect to /posts
        $response->assertRedirect('/categories');
    }


    /**
     * A basic feature test to display edit form post.
    */
    public function test_get_form_edit_category(): void
    {
        $category = Category::first();
        
        $response = $this->get('/categories/' . $category->id . '/edit');

        $response->assertStatus(200)
                ->assertViewIs('categories.edit')
                ->assertViewHas('category', $category);
    }

     /**
     * A basic feature test to update a post.
    */
    public function test_update_data_category(): void
    {
        $category = Category::findOrFail(2); // I try to get first category from database that will be updated

        $response = $this->put('/categories/' . $category->id, [
            'category_name' => 'Update Category name'
        ]);

        $response->assertStatus(302); // use http status 302 because after update a post, it will redirect to /posts
        $response->assertRedirect('/categories');
    }

    public function test_delete_category() : void 
    {
        // I use soft delete, so data won't be deleted permanently
        // I try to get post with id 2 from database that will be deleted
        $category = Category::findOrFail(4);

        $response = $this->delete('/categories/' . $category->id);

        $response->assertStatus(302); // use http status 302 because after delete a post, it will redirect to /posts
        $response->assertRedirect('/categories');
    }   
}