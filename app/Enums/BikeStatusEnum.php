<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum BikeStatusEnum: string implements HasLabel, HasColor
{


    case AVAILABLE = 'Available';
    case RENTED = 'Rented';
    case MAINTENANCE = 'Maintenance';
    case UNAVAILABLE = 'Unavailable';
    case MISSING_PARTS = 'Missing_Parts';


    //active disabled missing_parts maintenance

    public function getLabel(): ?string
    {
        //        return $this->name;
        return match ($this) {
            self::AVAILABLE => 'Disponível',
            self::RENTED => 'Alugada',
            self::MAINTENANCE => 'Manutenção',
            self::UNAVAILABLE => 'Indisponível',
            self::MISSING_PARTS => 'Falta Peças',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::AVAILABLE => 'success',
            self::RENTED => 'info',
            self::MAINTENANCE => 'warning',
            self::UNAVAILABLE => 'gray',
            self::MISSING_PARTS => 'danger',
        };
    }
}
