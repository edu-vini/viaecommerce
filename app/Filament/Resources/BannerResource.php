<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationGroup = 'Catálogo & Coleções';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banners & Destaques';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conteúdo do Banner')
                    ->description('Personalize o texto, imagem e chamada de ação.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título Principal')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('badge')
                            ->label('Selo / Badge Superior')
                            ->placeholder('Ex: ALTA COSTURA AUTORAL, EDIÇÃO LIMITADA')
                            ->maxLength(100),

                        Forms\Components\Textarea::make('subtitle')
                            ->label('Subtítulo / Texto Explicativo')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Imagem do Banner')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->required()
                            ->imagePreviewHeight('200')
                            ->helperText('Recomendado: 1920x800px para o banner principal.')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('button_text')
                            ->label('Texto do Botão')
                            ->placeholder('Ex: Descobrir Coleção, Ver Alfaiataria')
                            ->default('Ver Coleção'),

                        Forms\Components\TextInput::make('button_link')
                            ->label('Link de Destino')
                            ->placeholder('Ex: /catalogo ou /categoria/vestidos-midi')
                            ->default('/catalogo'),

                        Forms\Components\Select::make('position')
                            ->label('Posição na Loja')
                            ->options([
                                'home_hero' => 'Banner Principal do Topo (Hero)',
                                'home_featured' => 'Destaque de Meio (Lookbook & Conceito)',
                                'home_middle' => 'Faixa Promocional / Secundário',
                            ])
                            ->default('home_hero')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Ordem de Exibição')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Banner Ativo')
                            ->default(true)
                            ->columnSpanFull(),
                    ])->columns(3),
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
                    ->getStateUsing(fn (Banner $record) => $record->image_url),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Banner $record): string => $record->badge ? "[{$record->badge}] ".$record->subtitle : ($record->subtitle ?? '')),

                Tables\Columns\TextColumn::make('position')
                    ->label('Posição')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'home_hero' => 'Topo (Hero)',
                        'home_featured' => 'Lookbook Meio',
                        'home_middle' => 'Faixa Promocional',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'home_hero' => 'primary',
                        'home_featured' => 'gold',
                        default => 'info',
                    }),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('Botão')
                    ->description(fn (Banner $record) => $record->button_link),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
