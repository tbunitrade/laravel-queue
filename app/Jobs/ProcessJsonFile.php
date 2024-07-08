<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductPrice;
use Carbon\Carbon;
use Log;

class ProcessJsonFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        Log::info('Starting to process file: ' . $this->filePath);

        $fileContents = Storage::get($this->filePath);
        $products = json_decode($fileContents, true);

        foreach ($products as $productData) {
            Log::info('Processing product: ' . $productData['name']);

            $product = Product::firstOrNew(
                ['name' => $productData['name']]
            );
            $product->description = $productData['description'];
            $product->save();

            Log::info('Saved product: ' . $product->name);

            $productPrice = ProductPrice::firstOrNew(
                [
                    'product_id' => $product->id,
                    'date' => Carbon::parse($productData['date']),
                ]
            );
            $productPrice->price = $productData['price'];
            $productPrice->save();

            Log::info('Saved product price: ' . $productPrice->price);
        }

        Log::info('Finished processing file: ' . $this->filePath);
    }
}

