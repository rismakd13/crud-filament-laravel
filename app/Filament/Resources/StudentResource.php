<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Http\UploadedFile;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\RelationManagers\PostRelationManager;
use App\Filament\Resources\StudentResource\RelationManagers\PrestasiRelationManager;
use App\Filament\Resources\StudentResource\Widgets\StudentStatsOverview;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nim')->required()->unique(ignorable: fn
                            ($record) => $record),
                        TextInput::make('nama')->required(),
                        Select::make('fakultas')->options([
                            'MIPA'=>'MIPA',
                            'FTK'=>'FTK',
                            'FE'=>'FE',
                            'SOSHUM'=>'SOSHUM',
                            'FBS'=>'FBS'
                        ]),
                        Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan'
                            ])
                            ->required()
                            ->rules('required'),
                        TextInput::make('email')->required()->rules('required|email'),
                        TextInput::make('kelas'),
                        FileUpload::make('foto')
                        ->image()
                        ->imageResizeMode('foto')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('1080'),
                            // ->store(function (UploadedFile $file, array $values) {
                            //     // Logic to handle the uploaded image file
                            //     $path = $file->storePublicly('public/foto'); // Store in storage/app/public/foto
                            //     return ['foto' => $path];
                            // })
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nim')->sortable()->searchable(),
                TextColumn::make('nama')->sortable()->searchable(),
                TextColumn::make('fakultas')->sortable()->searchable(),
                TextColumn::make('jenis_kelamin')->sortable()->searchable(),
                TextColumn::make('kelas')->sortable()->searchable(),
                ImageColumn::make('foto'),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan'
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->bulkActions([
                // ButtonBulkAction::make(),
                ExportBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
             PrestasiRelationManager::class,
             PostRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            // 'view' => Pages\ViewStudent::route('/{record}')
        ];
    }
    
    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Infolists\Components\TextEntry::make('nim'),
    //             Infolists\Components\TextEntry::make('nama'),
    //             Infolists\Components\TextEntry::make('email'),
    //             Infolists\Components\TextEntry::make('jenis_kelamin'),
    //         ]);
    // }

    public static function getWidgets(): array
    {
        return [
            //StudentStatsOverview::class
        ];
    }
}
