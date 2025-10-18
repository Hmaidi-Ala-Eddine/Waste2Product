<?php

namespace App\Helpers;

class TunisiaStates
{
    /**
     * Get all Tunisia governorates (states)
     */
    public static function getStates(): array
    {
        return [
            'Tunis' => 'Tunis',
            'Ariana' => 'Ariana',
            'Ben Arous' => 'Ben Arous',
            'Manouba' => 'Manouba',
            'Nabeul' => 'Nabeul',
            'Zaghouan' => 'Zaghouan',
            'Bizerte' => 'Bizerte',
            'Béja' => 'Béja',
            'Jendouba' => 'Jendouba',
            'Kef' => 'Kef',
            'Siliana' => 'Siliana',
            'Kairouan' => 'Kairouan',
            'Kasserine' => 'Kasserine',
            'Sidi Bouzid' => 'Sidi Bouzid',
            'Sousse' => 'Sousse',
            'Monastir' => 'Monastir',
            'Mahdia' => 'Mahdia',
            'Sfax' => 'Sfax',
            'Gafsa' => 'Gafsa',
            'Tozeur' => 'Tozeur',
            'Kebili' => 'Kebili',
            'Gabès' => 'Gabès',
            'Medenine' => 'Medenine',
            'Tataouine' => 'Tataouine',
        ];
    }

    /**
     * Get states as array values (for validation)
     */
    public static function getStateValues(): array
    {
        return array_keys(self::getStates());
    }

    /**
     * Check if a state is valid
     */
    public static function isValidState(string $state): bool
    {
        return in_array($state, self::getStateValues());
    }
}
