<?php

namespace Database\Factories;

use App\Models\OfferDomain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfferDomain>
 */
class OfferDomainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name_x = ".".fake()->tld();
        return [
            'name_x' => $name_x,
            'topdomain_n' => 1,
            'bestellbar_n' => 1,
            'registrar_id' => 3,
            'anforderungen_x' => 0,
            'periode_n' => '12',
            'purchase_create_n' => rand(1, 100),
            'create_currency_n' => 2,
            'whois_server_x' => 'whois.nic'.$name_x,
            'whois_typ_x' => 'whois'
        ];
    }
}
