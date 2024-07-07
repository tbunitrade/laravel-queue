<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessJsonFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    /**
     * Create a new job instance.
     */
    public function __construct($path)
    {
        //
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $json   =   Storage::get($this->path);
        $data   =   json_decode($json, true);

        foreach ($data as $i) {
            $product = Product::updateOrCreate(
                ['name' => $i['name']],
                ['description' => $i['description']]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'date' => $i['date']],
                ['price' => $i['price']]
            );
        }

        CalculateAggregates::dispatch();
    }
}
