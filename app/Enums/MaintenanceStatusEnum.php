<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MaintenanceStatusEnum: string implements HasLabel, HasColor
{

    // 'pending', 'in_progress', 'completed']
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';


    //active disabled missing_parts maintenance

    public function getLabel(): ?string
    {
        //        return $this->name;
        return match ($this) {
            self::PENDING => 'Pendente',
            self::IN_PROGRESS => 'Em Andamento',
            self::COMPLETED => 'Concluído',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::IN_PROGRESS => 'info',
            self::COMPLETED => 'success',
        };
    }
}
