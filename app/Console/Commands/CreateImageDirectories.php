<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateImageDirectories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mk:imgdr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create image directories in public/images';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $directories = [
            'admin',
            'blog',
            'brand',
            'category',
            'customer',
            'email',
            'gallery',
            'middle-banner',
            'product',
            'slider',
            'slider-right-side-banner',
            'website',
        ];

        foreach ($directories as $directory) {
            $path = public_path('images/' . $directory);
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("Directory created: {$path}");
            } else {
                $this->info("Directory already exists: {$path}");
            }
        }

        $this->info('All directories have been created.');
    }
}
