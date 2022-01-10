<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Elasticsearch;
use Exception;

class IndexPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Post::all();

        $createdCount = 0;
        $failedCount = 0;

        foreach ($posts as $post) {
            try {
                Elasticsearch::index([
                    'id' => $post->id,
                    'index' => 'posts',
                    'body' => [
                        'title' => $post->title,
                        'content' => $post->content
                    ]
                ]);
                $createdCount++;
            } catch (Exception $e) {
                $failedCount++;
                $this->info($e->getMessage());
            }
        }

        $this->info("$createdCount posts were successfully indexed and $failedCount posts could not be indexed");
    }
}
