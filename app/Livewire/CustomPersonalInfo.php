<?php

namespace App\Livewire;

use App\Models\City;
use App\Models\Qualification;
use App\Models\State;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as LivewirePersonalInfo;
use Jeffgreco13\FilamentBreezy\PersonalInfo;

class CustomPersonalInfo extends LivewirePersonalInfo
{
    //protected string $view = "livewire.my-custom-component";

    public static $sort = 0;
    public array $only = [
        'name', 'email',
        'receive_email_notification',
        'receive_sms_notification',
        'registration_number',
        'phone',
        'gender', 'birthday', 'year_of_passed_out', 'address',
        'pincode', 'school', 'qualification_id', 'state_id', 'aadhaar_number', 'linkedin_profile',
        'upload_resume', 'upload_aadhar'
    ];
    //public bool $hasAvatars = true;

    public function getProfileFormSchema(): array
    {
        $groupFields = Forms\Components\Group::make([
            $this->getNameComponent()->disabled(),
            $this->getEmailComponent()->disabled(),
        ])->columnSpan(2);

        return [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields];
        return [
//            Forms\Components\Section::make()
//                ->schema([
//                    Forms\Components\TextInput::make('name')
//                        ->required()->columnSpan(2),
//                    Forms\Components\TextInput::make('email')
//                        ->required()->columnSpan(2),
//                    Forms\Components\Toggle::make('receive_email_notification')
//                        ->label('Receive Email notification.')
//                        ->required()
//                        ->columnSpan(2),
//                    Forms\Components\Toggle::make('receive_sms_notification')
//                        ->label('Receive SMS notification.')
//                        ->required()
//                        ->columnSpan(2)
//                ]),
//            Forms\Components\Section::make()
//                ->schema([
//                    Forms\Components\TextInput::make('registration_number')
//                        ->required()->columnSpan(2),
//                    Forms\Components\TextInput::make('phone')
//                        ->label('Contact Number')
//                        ->required()->columnSpan(2),
//                    Forms\Components\Select::make('gender')
//                        ->options(['Male' => 'Male', 'Female' => 'Female']),
//                    Forms\Components\DatePicker::make('birthday'),
//                ]),
//            Forms\Components\Section::make()
//                ->schema([
//
//                    Forms\Components\Select::make('qualification_id')
//                        ->label('Qualification')
//                        ->options(Qualification::pluck('name', 'id'))
//                        ->searchable()
//                        ->preload(),
//
//                    Forms\Components\TextInput::make('year_of_passed_out')
//                        ->numeric()
//                        ->maxLength(4),
//
//                    Forms\Components\Textarea::make('address')
//                        ->columnSpanFull(),
//
//
//                    Forms\Components\Select::make('city_id')
//                        ->label('City')
//                        ->options(City::pluck('name', 'id'))
//                        ->searchable()
//                        ->preload()
//                    ,
//
//                    Forms\Components\Select::make('state_id')
//                        ->label('State')
//                        ->options(State::pluck('name', 'id'))
//                        ->searchable()
//                        ->preload(),
//
//
//                    Forms\Components\TextInput::make('pincode')
//                        ->numeric()
//                        ->maxLength(6),
//                    Forms\Components\TextInput::make('school')
//                        ->maxLength(255)
//
//                ])->columns(2),
//
//            Forms\Components\Section::make()
//                ->schema([
//
//                    Forms\Components\TextInput::make('aadhaar_number')
//                        ->maxLength(255),
//                    Forms\Components\TextInput::make('linkedin_profile')
//                        ->url()
//                        ->maxLength(255),
//                ])->columns(2),
//
//            Forms\Components\Section::make()
//                ->schema([
//
//
//                    Forms\Components\FileUpload::make('upload_resume')
//                        ->inlineLabel(true)
//                        ->label('Resume'),
//                    Forms\Components\FileUpload::make('upload_aadhar')
//                        ->label('Aadhar')
//                        ->inlineLabel(true),
//                ])->columns(2),


        ];
    }

    public function submit(): void
    {

        $data = collect($this->form->getState())->all();
        //dd($data);
        /*$user = User::find($this->user->id);
        $user->fill($data);
        $user->save();*/
        //$this->user->refresh();
        $this->user->update($data);
        $this->sendNotification();
    }

    protected function sendNotification(): void
    {
        Notification::make()
            ->success()
            ->title('Saved Data!')
            ->send();
    }
}
