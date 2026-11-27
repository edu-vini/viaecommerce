<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catálogo & Coleções';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $pluralModelLabel = 'Produtos & Catálogo';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Detalhes do Produto')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('1. Informações Básicas')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Título do Produto')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (Set $set, ?string $state, $record) {
                                                if (! $record) {
                                                    $set('slug', Str::slug($state));
                                                    if (! $set('sku')) {
                                                        $set('sku', 'SKU-'.strtoupper(Str::random(6)));
                                                    }
                                                }
                                            })
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('category_id')
                                            ->label('Categoria')
                                            ->relationship('category', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),

                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('slug')
                                            ->label('Slug / URL Amigável')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Product::class, 'slug', ignoreRecord: true),

                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU Principal')
                                            ->required()
                                            ->maxLength(100)
                                            ->unique(Product::class, 'sku', ignoreRecord: true)
                                            ->helperText('Código único do produto no estoque.'),

                                        Forms\Components\TextInput::make('brand')
                                            ->label('Marca / Ateliê')
                                            ->default('Elisie')
                                            ->maxLength(100),

                                        Forms\Components\TextInput::make('badge')
                                            ->label('Selo / Badge de Destaque')
                                            ->placeholder('Ex: NOVA COLEÇÃO, ALTA COSTURA')
                                            ->maxLength(50),
                                    ]),

                                Forms\Components\Textarea::make('summary')
                                    ->label('Resumo Curto (Lookbook & Cards)')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('description')
                                    ->label('Descrição Completa do Produto')
                                    ->rows(5)
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('model_info')
                                    ->label('Medidas e Informações da Modelo')
                                    ->placeholder('Ex: A modelo mede 1,73m, tem 86cm de busto e veste P.')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('2. Preços & Estoque')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('price')
                                            ->label('Preço de Venda (R$)')
                                            ->numeric()
                                            ->prefix('R$')
                                            ->required()
                                            ->helperText('Preço cobrado na finalização do pedido.'),

                                        Forms\Components\TextInput::make('original_price')
                                            ->label('Preço Original (De / Riscado)')
                                            ->numeric()
                                            ->prefix('R$')
                                            ->helperText('Se informado, ativa automaticamente a tag promocional.'),

                                        Forms\Components\TextInput::make('cost_price')
                                            ->label('Preço de Custo (Interno)')
                                            ->numeric()
                                            ->prefix('R$')
                                            ->helperText('Uso administrativo para cálculo de margem.'),
                                    ]),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Estoque Total Geral')
                                            ->numeric()
                                            ->default(10)
                                            ->required()
                                            ->helperText('Controle de estoque unificado.'),

                                        Forms\Components\TextInput::make('rating')
                                            ->label('Avaliação Média')
                                            ->numeric()
                                            ->default(5.00)
                                            ->step(0.01)
                                            ->minValue(1)
                                            ->maxValue(5),

                                        Forms\Components\TextInput::make('reviews_count')
                                            ->label('Total de Avaliações')
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                Forms\Components\Section::make('Visibilidade e Destaques')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Produto Ativo na Loja')
                                            ->default(true),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Destaque na Página Inicial')
                                            ->default(false),

                                        Forms\Components\Toggle::make('is_best_seller')
                                            ->label('Mais Vendidos')
                                            ->default(false),

                                        Forms\Components\Toggle::make('is_new')
                                            ->label('Lançamento / Novidade')
                                            ->default(true),

                                        Forms\Components\Toggle::make('is_deal')
                                            ->label('Oferta Especial')
                                            ->default(false),
                                    ])->columns(5),
                            ]),

                        Forms\Components\Tabs\Tab::make('3. Fotos da Peça (Galeria)')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->label('Fotos do Produto (Upload Múltiplo)')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->disk('public')
                                    ->directory('products')
                                    ->imagePreviewHeight('220')
                                    ->helperText('Arraste para reordenar. A primeira foto será a foto principal do catálogo e dos cards.')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('4. Variações & Grades')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Forms\Components\Repeater::make('variations')
                                    ->relationship('variations')
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->label('Tipo de Grade')
                                            ->options([
                                                'Tamanho' => 'Tamanho',
                                                'Cor' => 'Cor',
                                                'Voltagem' => 'Voltagem',
                                                'Outro' => 'Outro',
                                            ])
                                            ->default('Tamanho')
                                            ->required(),

                                        Forms\Components\TextInput::make('label')
                                            ->label('Variação / Tamanho')
                                            ->placeholder('Ex: P (38), M (40), G (42)')
                                            ->required(),

                                        Forms\Components\TextInput::make('sku')
                                            ->label('SKU da Variação')
                                            ->placeholder('Ex: VES-01-P'),

                                        Forms\Components\TextInput::make('price_delta')
                                            ->label('Diferença Preço (R$)')
                                            ->numeric()
                                            ->default(0.00)
                                            ->prefix('R$'),

                                        Forms\Components\TextInput::make('stock')
                                            ->label('Estoque da Grade')
                                            ->numeric()
                                            ->default(5)
                                            ->required(),

                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Ativo')
                                            ->default(true),
                                    ])
                                    ->columns(6)
                                    ->defaultItems(3)
                                    ->addActionLabel('Adicionar Variação / Tamanho')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('5. Cores & Ficha Técnica')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                Forms\Components\Repeater::make('colors')
                                    ->label('Opções de Cores Disponíveis')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nome da Cor')
                                            ->placeholder('Ex: Off-White Linho, Bordeaux Nobre')
                                            ->required(),

                                        Forms\Components\ColorPicker::make('hex')
                                            ->label('Código Hexadecimal')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Adicionar Cor')
                                    ->columnSpanFull(),

                                Forms\Components\KeyValue::make('specifications')
                                    ->label('Especificações Técnicas')
                                    ->keyLabel('Característica (Ex: Tecido, Forro, Decote)')
                                    ->valueLabel('Detalhe (Ex: 100% Linho Puro)')
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('measurement_table')
                                    ->label('Tabela de Medidas (Guia de Tamanhos)')
                                    ->schema([
                                        Forms\Components\TextInput::make('size')->label('Tamanho')->placeholder('P (38)'),
                                        Forms\Components\TextInput::make('bust')->label('Busto')->placeholder('88-92 cm'),
                                        Forms\Components\TextInput::make('waist')->label('Cintura')->placeholder('68-72 cm'),
                                        Forms\Components\TextInput::make('hip')->label('Quadril')->placeholder('96-100 cm'),
                                        Forms\Components\TextInput::make('length')->label('Comprimento')->placeholder('119 cm'),
                                    ])
                                    ->columns(5)
                                    ->addActionLabel('Adicionar Linha de Medida')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Foto')
                    ->circular(false)
                    ->square()
                    ->getStateUsing(fn (Product $record) => $record->primary_image),

                Tables\Columns\TextColumn::make('title')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap()
                    ->description(fn (Product $record): string => "SKU: {$record->sku} | Marca: {$record->brand}"),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Preço Venda')
                    ->money('BRL')
                    ->sortable()
                    ->description(fn (Product $record) => $record->original_price ? 'De: R$ '.number_format($record->original_price, 2, ',', '.') : null),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Estoque')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state) => "{$state} un."),

                Tables\Columns\TextColumn::make('badge')
                    ->label('Destaque')
                    ->badge()
                    ->color('gold')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Apenas Ativos')
                    ->falseLabel('Inativos'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Estoque Baixo (<= 5 un.)')
                    ->query(fn ($query) => $query->where('stock', '<=', 5)),
            ])
            ->actions([
                Tables\Actions\Action::make('view_store')
                    ->label('Ver na Loja')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Product $record): string => route('product.show', ['slug' => $record->slug]), shouldOpenInNewTab: true),

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
