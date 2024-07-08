<?php
namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models.ProductPriceAggregate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CalculateAggregates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $periods = [
            '3_days' => 3,
            '7_days' => 7,
            '30_days' => 30,
        ];

        foreach (Product::all() as $product) {
            foreach ($periods as $period => $days) {
                // Использование Carbon для вычисления даты, которая находится на $days назад от текущей даты
                $averagePrice = ProductPrice::where('product_id', $product->id)
                    ->where('date', '>=', Carbon::today()->subDays($days))
                    ->average('price');

                // Обновление или создание записи в таблице агрегированных данных
                ProductPriceAggregate::updateOrCreate(
                    ['product_id' => $product->id, 'period' => $period],
                    ['average_price' => $averagePrice, 'calculated_at' => Carbon::now()]
                );
            }
        }
    }
}
