<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchStudentResource\Pages;
use App\Models\Batch;
use App\Models\Branch;
use App\Models\CourseStudent;
use App\Models\User;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchStudentResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $slug = 'master/batches';

    protected static ?string $navigationGroup = 'My Courses';
    protected static ?string $label = 'My Courses';

    protected static ?int $navigationSort = -2;

    protected static bool $isScopedToTenant = false;

    public static function canAccess(): bool
    {
        //return true;
        return (bool) auth()->user()->is_student;
    }

    /*public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }*/

    public static function table(Table $table): Table
    {
        $livewire = $table->getLivewire();

        return $table
            ->columns(
                [
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\ImageColumn::make('course_package.image')
                            ->defaultImageUrl(url('/images/placeholder.jpg'))
                            ->square()
                            ->height(200)
                            ->width('100%'),
                        Tables\Columns\TextColumn::make('course_package.name')
                            ->searchable()
                            ->weight(FontWeight::SemiBold)
                            ->limit(50),
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->weight(FontWeight::SemiBold)
                            ->limit(50),

                    ])
                ]
            )
            ->contentGrid([
                'sm' => 2,
                'md' => 2,
                'lg' => 3,
                'xl' => 4,
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                /*  Tables\Actions\BulkActionGroup::make([
                      Tables\Actions\DeleteBulkAction::make(),
                  ]),*/
            ]);
//            ->recordUrl(
//                fn(CourseStudent $record): string => route('filament.administrator.resources.courses.curriculums',
//                    ['record' => $record, 'tenant' => Filament::getTenant()])
//            );
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            //'students' => Pages\ManageStundents::route('/{record}/students'),
            //'create' => Pages\CreateBatch::route('/create'),
            'view' => Pages\ViewBatch::route('/{record}'),
            //'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            //Pages\ViewBatch::class,
            //Pages\EditBatch::class,
            //Pages\ManageStundents::class

        ]);
    }
}
