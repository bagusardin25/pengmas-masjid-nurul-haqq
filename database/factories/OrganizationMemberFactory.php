<?php

namespace Database\Factories;

use App\Enums\PublicationStatus;
use App\Models\OrganizationMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganizationMember>
 */
class OrganizationMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'position' => 'Ketua',
            'name' => 'Ahmad Fauzi',
            'sort_order' => 0,
            'status' => PublicationStatus::Draft,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => PublicationStatus::Published,
        ]);
    }
}
