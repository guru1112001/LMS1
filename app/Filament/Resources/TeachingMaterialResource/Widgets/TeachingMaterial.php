<?php

namespace App\Filament\Resources\TeachingMaterialResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TeachingMaterialResource;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TeachingMaterial as TeachingMaterialModel;

class TeachingMaterial extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(TeachingMaterialResource::getEloquentQuery())
            //->defaultPaginationPageOption(5)
            /*->defaultSort('created_at', 'desc')*/
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
            ])
//            ->actions([
//                Tables\Actions\Action::make('open')
//                    ->url(fn (TeachingMaterialModel $record): string => TeachingMaterialResource::getUrl('edit', ['record' => $record])),
//            ])
;
    }
}
