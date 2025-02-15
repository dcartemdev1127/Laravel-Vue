<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    private array $packages = [
        [
            'name' => 'Bronze Account',
            'short_desc' => 'Basic features for up to 10 users',
            'description' => '["Access to basic features","Basic reporting and analytics","Up to 10 individual users","20GB individual data each user","Basic chat and email support"]',
            'currency' => '£',
            'monthly_price' => 0,
            'annual_price' => 0,
            // 'monthly_price_id' => 'price_1QKN1xDiOLEEk84OUM8npDOD',
            // 'yearly_price_id' => 'price_1QKN1yDiOLEEk84OXdgSsAHN',
            // 'monthly_price_paypal_id' => 'P-99031249LH0595135M44M6CQ',
            // 'yearly_price_paypal_id' => 'P-9K071395EG922864YM44M6CY',
            'monthly_price_id' => 'price_1QPlD5DiOLEEk84OFlfDNJmh',
            'yearly_price_id' => 'price_1QPlD6DiOLEEk84ORRG8bBte',
            'monthly_price_paypal_id' => '',
            'yearly_price_paypal_id' => '',
            'active' => 1,
            'percent' => 50
        ],
        [
            'name' => 'Silver Account',
            'short_desc' => 'Advanced features and reporting',
            'description' => '["Access to basic features","Basic reporting and analytics","Up to 10 individual users","20GB individual data each user","Basic chat and email support"]',
            'currency' => '£',
            'monthly_price' => 15,
            'annual_price' => 99,
            // 'monthly_price_id' => 'price_1QKMo1DiOLEEk84OGPs7cMwk',
            // 'yearly_price_id' => 'price_1QKMo1DiOLEEk84ODw9ZO80R',
            // 'monthly_price_paypal_id' => 'P-8PY36665W3613194JM44MXUI',
            // 'yearly_price_paypal_id' => 'P-9HM35079GG199774RM44MXUQ',
            'monthly_price_id' => 'price_1QPlE1DiOLEEk84OXQ8Ic0ok',
            'yearly_price_id' => 'price_1QPlE1DiOLEEk84O6xSwYDgQ',
            'monthly_price_paypal_id' => '',
            'yearly_price_paypal_id' => '',
            'active' => 1,
            'percent' => 35
        ],
        [
            'name' => 'Gold Account',
            'short_desc' => 'Unlimited features',
            'description' => '["Access to basic features","Basic reporting and analytics","Up to 10 individual users","20GB individual data each user","Basic chat and email support"]',
            'currency' => '£',
            'monthly_price' => 45,
            'annual_price' => 259,
            // 'monthly_price_id' => 'price_1QKMsdDiOLEEk84OxUCLjMt0',
            // 'yearly_price_id' => 'price_1QKMsdDiOLEEk84O1MJbcMxt',
            // 'monthly_price_paypal_id' => 'P-6CG97785BE525531PM44MWEY',
            // 'yearly_price_paypal_id' => 'P-18C85024W14634216M44MWFA',
            'monthly_price_id' => 'price_1QPlEXDiOLEEk84OjMPj6QWi',
            'yearly_price_id' => 'price_1QPlEYDiOLEEk84OKYHPkrOK',
            'monthly_price_paypal_id' => '',
            'yearly_price_paypal_id' => '',
            'active' => 1,
            'percent' => 20
        ]
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->packages as $package) {
            $newPakcage = new Package($package);
            $newPakcage->save();
        }
    }
}