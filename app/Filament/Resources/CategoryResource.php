<?php

namespace App\Filament\Resources;

use Str;
use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

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
                                $set('slug', \Str::slug($state['name']));
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
            // ->schema([
            //     Card::make()->schema([
            //         Select::make('category_id')
            //             ->relationship('category', 'name'),
            //         TextInput::make('title')
            //             ->reactive()
            //             ->afterStateUpdated(function ($set, $state) {
            //                 if (is_array($state) && isset($state['name'])) {
            //                     $set('slug', \Str::slug($state['name']));
            //                 }
            //             })->required(),
            //         TextInput::make('slug')->required(),
            //         FileUpload::make('cover'),
            //         RichEditor::make('content'),
            //         Toggle::make('status'),
            //     ])
            // ])

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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
}
