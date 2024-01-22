<?php

namespace App\Filament\Resources;

use Str;
use App\Models\Tag;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TagResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TagResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
                ->schema([
                    TextInput::make('name')
                        ->reactive()
                        ->afterStateUpdated(function ($set, $state) {
                            if (is_array($state) && isset($state['name'])) {
                                $set('slug', Str::slug($state['name']));
                            }
                        })
                        ->required(),
                    TextInput::make('slug')->required(),
                ])
                // ->columns(2),
        ]);    
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('No')->sortable()->searchable()->getStateUsing(
                static function ($rowLoop, HasTable $livewire): string {
                    return (string) (
                        $rowLoop->iteration +
                        ($livewire->tableRecordsPerPage * (
                            $livewire->page - 1
                        ))
                    );
                }
            ),
            TextColumn::make('name')->limit('50')->sortable()->searchable(),
            TextColumn::make('slug')->limit('50')->sortable()->searchable(),
             ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }    
}
