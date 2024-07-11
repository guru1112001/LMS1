<?php
// namespace App\Filament\Pages\Tenancy;

// use App\Models\Branch;
// use App\Models\Team;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\TextArea;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\Toggle;
// use Filament\Forms\Form;
// use Filament\Pages\Tenancy\RegisterTenant;
// use Illuminate\Database\Eloquent\Model;
 
// class RegisterTeam extends RegisterTenant
// {
//     public static function getLabel(): string
//     {
//         return 'Create branch';
//     }
    
//     public function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 TextInput::make('name'),
//                 TextInput::make('slug'),
            
//                         TextInput::make('contact_number')
//                             ->required()
//                             ->maxLength(255),
//                         Textarea::make('address')
//                             ->required()
//                             ->maxLength(65535)
//                             ->columnSpanFull(),
//                         Select::make('country_id')
//                             ->relationship('country', 'name')
//                             ->searchable()
//                             ->preload()
//                             ->createOptionForm([
//                                 TextInput::make('name')
//                                     ->required(),
//                             ]),

//                         // Select::make('city_id')
//                         //     ->relationship('city', 'name')
//                         //     ->searchable()
//                         //     ->preload()
//                         //     ->createOptionForm([
//                         //         TextInput::make('name')
//                         //             ->required(),
//                         //     ]),

//                         // Select::make('state_id')
//                         //     ->required()
//                         //     ->relationship('state', 'name')
//                         //     ->searchable()
//                         //     ->preload()
//                         //     ->createOptionForm([
//                         //         TextInput::make('name')
//                         //             ->required(),
//                         //     ]),


//                         // TextInput::make('pincode')
//                         //     ->required()
//                         //     ->numeric()
//                         //     ->maxLength(6),
//                         // TextInput::make('website')
//                         //     ->required()
//                         //     ->maxLength(255),
//                         // Toggle::make('online_branch')
//                         //     ->required(),
//                         // Toggle::make('allow_registration')
//                         //     ->required(),        
//             ]);
//     }
    
//     protected function handleRegistration(array $data): Team
//     {
//         $team = Branch::create($data);
        
//         $team->members()->attach(auth()->user());
        
//         return $team;
//     }
// }