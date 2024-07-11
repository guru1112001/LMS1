<?php


namespace App\Models\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LeaveStatus: string implements HasColor, HasLabel
{
    case Pending = 'Pending';

    case Declined = 'Declined';

    case Approved = 'Approved';


    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Declined => 'Decline',
            self::Approved => 'Approve',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Declined => 'danger',
        };
    }
}
