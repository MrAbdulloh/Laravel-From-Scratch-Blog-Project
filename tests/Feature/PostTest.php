<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function can_create_post()
    {

        $admin = User::find(1);
        Storage::fake('public');
        $file = UploadedFile::fake()->create('image.jpg');
        $response = $this->actingAs($admin)->post('admin/posts', [
            'title' => 'misol',
            'thumbnail' => $file,
            'slug' => random_int(100, 1000),
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => 1,
        ]);
        Storage::disk('public')->assertExists('thumbnails/',$file->hashName());
        $response->assertRedirect('/');

        $this->assertDatabaseCount('posts', 6);

        $this->assertDatabaseHas('posts',[
            'title' => 'misol',
        ]);

    }

}
