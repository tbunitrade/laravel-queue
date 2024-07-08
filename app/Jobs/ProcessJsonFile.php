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

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        $json = Storage::get($this->path);
        $data = json_decode($json, true);

        foreach ($data as $item) {
            $product = Product::updateOrCreate(
                ['name' => $item['name']],
                ['description' => $item['description']]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'date' => $item['date']],
                ['price' => $item['price']]
            );
        }

        CalculateAggregates::dispatch();
    }
}
