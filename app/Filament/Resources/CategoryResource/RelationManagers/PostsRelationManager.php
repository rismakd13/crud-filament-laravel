<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()->schema([
                Select::make('category_id')
                    ->relationship('category', 'name'),
                TextInput::make('title')
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        if (is_array($state) && isset($state['name'])) {
                            $set('slug', \Str::slug($state['name']));
                        }
                    })->required(),
                TextInput::make('slug')->required(),
                // SpatieMediaLibraryfileUpload::make('cover')->sortable()->searchable(),
                FileUpload::make('cover'),
                    //  ->image()
                    //  ->imageEditor(),
                RichEditor::make('content'),
                Toggle::make('status'),
            ])
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
            TextColumn::make('title')->limit('50')->sortable()->searchable(),
            TextColumn::make('category.name')->sortable()->searchable(),
            // SpatieMediaLibraryImageColumn::make('cover')->sortable()->searchable(),
            FileUpload::make('cover')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('1080'),
            ToggleColumn::make('status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
