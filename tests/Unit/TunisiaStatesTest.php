<?php

namespace Tests\Unit;

use App\Helpers\TunisiaStates;
use Tests\TestCase;

class TunisiaStatesTest extends TestCase
{
    /** @test */
    public function it_returns_all_tunisia_governorates()
    {
        $states = TunisiaStates::getStates();

        $this->assertIsArray($states);
        $this->assertCount(24, $states);
        $this->assertContains('Tunis', $states);
        $this->assertContains('Ariana', $states);
        $this->assertContains('Sfax', $states);
    }

    /** @test */
    public function it_returns_state_values_as_array()
    {
        $values = TunisiaStates::getStateValues();

        $this->assertIsArray($values);
        $this->assertEquals([
            'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'Nabeul', 'Zaghouan',
            'Bizerte', 'Béja', 'Jendouba', 'Kef', 'Siliana', 'Kairouan',
            'Kasserine', 'Sidi Bouzid', 'Sousse', 'Monastir', 'Mahdia', 'Sfax',
            'Gafsa', 'Tozeur', 'Kebili', 'Gabès', 'Medenine', 'Tataouine'
        ], $values);
    }

    /** @test */
    public function it_has_exactly_24_governorates()
    {
        $count = count(TunisiaStates::getStateValues());

        $this->assertEquals(24, $count);
    }
}
