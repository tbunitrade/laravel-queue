<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductPriceAggregate;
use Carbon\Carbon;
use Log;

class CalculateAggregates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Starting to calculate aggregates.');

        $periods = [
            '3_days' => 3,
            '7_days' => 7,
            '30_days' => 30,
        ];

        foreach (Product::all() as $product) {
            Log::info('Processing product: ' . $product->name);

            foreach ($periods as $period => $days) {
                $averagePrice = ProductPrice::where('product_id', $product->id)
                    ->where('date', '>=', Carbon::today()->subDays($days))
                    ->average('price');

                Log::info('Calculated average price for period ' . $period . ': ' . $averagePrice);

                ProductPriceAggregate::updateOrCreate(
                    ['product_id' => $product->id, 'period' => $period],
                    ['average_price' => $averagePrice, 'calculated_at' => now()]
                );

                Log::info('Saved aggregate data for period ' . $period);
            }
        }

        Log::info('Finished calculating aggregates.');
    }
}
