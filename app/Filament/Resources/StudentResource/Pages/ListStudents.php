<?php

namespace App\Filament\Resources\StudentResource\Pages;

use file;
use filament;
use Resources\view;
use Actions\ImportAction;
use Filament\Pages\Actions;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StudentResource;
use Illuminate\Contracts\View\View as IlluminateView;
use App\Filament\Resources\StudentResource\Actions as StudentActions;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ButtonAction::make('Import'),
            Actions\CreateAction::make(),
        ];
    }

    // public function getHeader(): ?View
    // {
    //     $data = StudentActions::make();
    //     return view('filament.custom.upload-file', compact('data') );
    // }


    public function getHeaderActions(): ?View
    {
        return view('filament.custom.upload-file.blade.php', compact('data'));
    }

    public $file = '';

    public function save(){
        Student::create([
            'nis' => '1234',
            'nama' => 'First',
            'fakultas' => 'FK',
        ]);
    }
}

