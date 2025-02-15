<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    private array $currencies = [
        ['currency' => 'USD', 'price' => 1],
        ['currency' => 'GBP', 'price' => 0.818988],
        ['currency' => 'EUR', 'price' => 0.975188]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->currencies as $item) {
            $currency = new Currency($item);
            $currency->save();
        }
    }
}
