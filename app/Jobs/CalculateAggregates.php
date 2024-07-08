<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductPriceAggregate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CalculateAggregates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $periods = [
            '3_days' => 3,
            '7_days' => 7,
            '30_days' => 30,
        ];

        foreach (Product::all() as $product)
        {
            foreach ($periods as $period => $days)
            {
                $averagePrice = ProductPrice::where('product_id', $product->id)
                    ->where('date', '>=', Carbon::today()->subDays($days))
                    ->average('price');

                ProductPriceAggregate::updateOrCreate(
                    ['product_id' => $product->id, 'period' => $period],
                    ['average_price' => $averagePrice, 'calculated_at' => now()]
                );
            }
        }
    }
}
