<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use App\Models\Course;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\FontWeight;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Announcement';

    protected static bool $isScopedToTenant = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DateTimePicker::make('schedule_at')
                            ->native(false)
                            ->default(now())
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->columnSpanFull(),
                        //                        Forms\Components\Radio::make('urgency')
                        //                            ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'])
                        //                            ->required(),
                        Forms\Components\Radio::make('visibility')
                            ->label('Announcement visibility')
                            ->hidden(!auth()->user()->is_admin)
                            ->options([
                                'existing_user' => 'Visible to existing users only',
                                'both' => 'Visible to both existing and new users in future'
                            ])
                            ->required(),
                        Forms\Components\Radio::make('audience')
                            ->options([
                                'all' => 'All Students',
                                'course_wise' => 'Course Wise'
                            ])
                            ->reactive()
                            ->hidden(!auth()->user()->is_admin)
                            ->required(),
                        Forms\Components\Select::make('course_id')
                            ->label('Course')
                            ->options(\App\Models\Course::all()->pluck('name', 'id'))
                            ->preload()
                            ->hidden(fn(Forms\Get $get): bool => $get('audience') != 'course_wise' || !auth()->user()->is_admin)
                            ->required(),
                        Forms\Components\Select::make('batch_ids')
                            ->options(function (callable $get) {
                                $courseId = $get('course_id');
                                if ($courseId) {
                                    return \App\Models\Course::with('batches')->find($courseId)->batches->pluck('name', 'id');
                                }
                                return \App\Models\Batch::all()->pluck('name', 'id');
                            })
                            ->preload()
                            ->multiple()
                            ->hidden(fn(Forms\Get $get): bool => $get('audience') != 'course_wise' || !auth()->user()->is_admin)
                            ->required()
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->height(100)->width(100),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Announcement $record) => new HtmlString($record->description
                    . '<br>' .
                        (auth()->user()->is_admin ?
                        Carbon::parse($record->schedule_at)
                            ->format('d M Y h:i')
                        : Carbon::parse($record->schedule_at)
                            ->format('d M Y'))))
                    ->searchable(),
                //Tables\Columns\TextColumn::make('urgency'),
                //Tables\Columns\TextColumn::make('visibility'),
                //Tables\Columns\TextColumn::make('audience'),
                /*Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\ImageEntry::make('image')
                    ->label(false)
                    //->width(100)
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('title')
                    ->label(false)
                    ->weight(FontWeight::SemiBold)
                    ->columnSpanFull(),

                Infolists\Components\TextEntry::make('schedule_at')
                    ->label(false)
                    ->formatStateUsing(function (Announcement $record) {
                        // Convert the schedule_at string to a Carbon instance and then format it
                        return Carbon::parse($record->schedule_at)
                            ->format('d M Y');
                    })
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('description')
                    ->label(false)
                    ->columnSpanFull(),
            ])->columns(2);
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
            'index' => Pages\ListAnnouncements::route('/'),
            //'create' => Pages\CreateAnnouncement::route('/create'),
            //'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
