<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyExchangeRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-exchange-rate';

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
        $app_id = config('global.exchangerate_app_id');
        if($app_id) {
            $url = "https://openexchangerates.org/api/latest.json?app_id=$app_id";
            $response = Http::get($url);
            if($response->successful()) {
                $exchangeRates = $response->json('rates');
                foreach($exchangeRates as $key => $value) {
                    $record = Currency::where('currency', $key)->first();
                    if($record) {
                        $record->price = floatval($value);
                        $record->save();
                    } else {
                        $record = new Currency([
                            'currency' => $key,
                            'price' => $value
                        ]);
                        $record->save();
                    }
                }
            }
        }
    }
}
