<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class CourseMaster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Course';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'master/course';
    protected static ?string $label = 'Courses';

    public static function getNavigationLabel(): string
    {
        return "Courses";
    }
}
