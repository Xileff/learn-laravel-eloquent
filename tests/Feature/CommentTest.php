<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testCreate()
    {
        $comment = new Comment();
        $comment->email = 'xilef@gmail.com';
        $comment->title = 'Sample Title';
        $comment->comment = 'Sample Comment';
        $comment->save();

        $this->assertNotNull($comment->id);
    }

    public function testCreateDefaultValue()
    {
        $comment = new Comment();
        $comment->email = 'xilef@gmail.com';
        $comment->save();

        $this->assertNotNull($comment->id);
        $this->assertEquals('Sample Title', $comment->title);
        $this->assertEquals('Sample Comment', $comment->comment);
    }
}
