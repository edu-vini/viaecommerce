<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catálogo & Coleções';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias & Subcategorias';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hierarquia e Identificação')
                    ->description('Organize categorias principais e subcategorias da sua loja.')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Categoria Pai (Hierarquia)')
                            ->relationship('parent', 'name', fn ($query, $record) => $record ? $query->where('id', '!=', $record->id) : $query)
                            ->placeholder('Nenhuma (Categoria Principal)')
                            ->searchable()
                            ->preload()
                            ->helperText('Deixe vazio para criar uma categoria de nível superior, ou selecione a categoria pai para criar uma subcategoria.'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nome da Categoria')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug / URL Amigável')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        Forms\Components\Select::make('icon')
                            ->label('Ícone Decorativo')
                            ->options([
                                'sparkles' => '✨ Brilho / Alta Costura',
                                'briefcase' => '💼 Alfaiataria / Trabalho',
                                'layers' => '📚 Conjuntos / Camadas',
                                'heart' => '❤️ Festa / Romântico',
                                'bookmark' => '🔖 Saias & Clássicos',
                                'sun' => '☀️ Blusas & Verão',
                                'tag' => '🏷️ Promoção / Coleção',
                            ])
                            ->searchable(),

                        Forms\Components\Textarea::make('description')
                            ->label('Descrição da Categoria')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Mídia & Exibição')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagem de Capa')
                            ->image()
                            ->disk('public')
                            ->directory('categories')
                            ->imagePreviewHeight('200')
                            ->helperText('Imagem exibida no lookbook e carrossel de categorias.')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Ativo na Loja')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=100&q=80'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Category $record): string => $record->parent ? '↳ Subcategoria de: '.$record->parent->name : '★ Categoria Principal'),

                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Hierarquia')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'primary')
                    ->formatStateUsing(fn ($state) => $state ? "↳ {$state}" : 'Nível Superior')
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL Slug')
                    ->copyable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Apenas Ativos')
                    ->falseLabel('Inativos'),

                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Filtrar por Categoria Pai')
                    ->relationship('parent', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
