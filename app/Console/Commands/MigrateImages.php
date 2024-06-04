<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MigrateImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = \App\Models\Product::where('active',1)->get();
        foreach ($products as $product) {
            $img = 'https://entradessolsones.com/images/' . $product->name . '.jpg';
            $path = "thumbnails/{$product->name}.jpg";
            $response = Http::get($img);
            if ($response->successful()) {
                Storage::put($path, $response->body());
                $this->info("Image uploaded successfully.");
            } else {
                $this->error("Error uploading image.");
            }
            $product->image = $path;
            $product->save();
            $this->info($img);
        }
    }
}
