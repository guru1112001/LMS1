<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends EditRecord
{
    protected static string $resource = UserResource::class;

//    protected function getHeaderActions(): array
//    {
//        return [
//            Actions\DeleteAction::make(),
//        ];
//    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn (string $context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                            ->label('Password')
                            ->reactive()
                            ->validationAttribute('password'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->label('Confirm Password')
                            ->required(fn (string $context) => $context === 'create')
                            ->reactive()
                            ->same('password')
                            ->validationAttribute('confirm password'),

//                        TextInput::make('password')
//                            ->password()
//                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
//                            ->dehydrated(fn($state) => filled($state))
                    ])
            ])
            ->columns(1);
    }

}
