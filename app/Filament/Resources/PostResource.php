<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Http\UploadedFile;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
// use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
// use Filament\Forms\Components\SpatieMediaLibraryfileUpload;
use App\Filament\Resources\PostResource\Widgets\StatsOverview;
use App\Filament\Resources\PostResource\RelationManagers\TagsRelationManager;


class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                    FileUpload::make('cover')
                        ->image()
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1920')
                        ->imageResizeTargetHeight('1080'),
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
                TextColumn::make('slug')->sortable()->searchable(),
                ImageColumn::make('cover'),
                TextColumn::make('content')->sortable()->searchable(),
                ToggleColumn::make('status')->sortable()->searchable(),
                ])
            ->filters([
                Filter::make('publish')
                    ->query(fn (Builder $query): Builder => $query->where('status', true)),
                
                Filter::make('draft')  // Fix the typo here
                    ->query(fn (Builder $query): Builder => $query->where('status', false)),

                SelectFilter::make('Category')->relationship('category', 'name'),
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
            TagsRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
    
    public static function getWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
}

