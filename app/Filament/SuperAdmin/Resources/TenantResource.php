<?php

declare(strict_types=1);

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\TenantResource\Pages;
use App\Models\Tenant;
use App\Services\StoreTemplateService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Gestão SaaS';

    protected static ?string $modelLabel = 'Empresa';

    protected static ?string $pluralModelLabel = 'Empresas (Tenants)';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $baseDomain = config('tenancy.base_domain', 'viasolutions.com.br');

        return $form
            ->schema([
                Forms\Components\Section::make('Dados da Empresa')
                    ->description('Identificação da loja e subdomínio dinâmico na plataforma.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome da Empresa / Loja')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation): void {
                                if ($operation === 'create' && filled($state)) {
                                    $slug = Str::slug($state);
                                    $set('slug', $slug);
                                    $set('id', $slug);
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('Subdomínio')
                            ->required()
                            ->alphaDash()
                            ->maxLength(63)
                            ->unique(ignoreRecord: true)
                            ->suffix(".{$baseDomain}")
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, Set $set, string $operation): void {
                                if ($operation === 'create' && filled($state)) {
                                    $set('id', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('id')
                            ->label('ID do Tenant')
                            ->required()
                            ->alphaDash()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('document')
                            ->label('CNPJ / CPF')
                            ->maxLength(20),

                        Forms\Components\TextInput::make('email')
                            ->label('E-mail do Responsável')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Telefone / WhatsApp')
                            ->maxLength(30),
                    ]),

                Forms\Components\Section::make('Modelo de Loja (Tema por Segmento)')
                    ->description('Escolha um modelo pronto com cores, identidade visual e produtos de demonstração, ou comece com a loja em branco.')
                    ->visibleOn('create')
                    ->schema([
                        Forms\Components\Select::make('template')
                            ->label('Tema / Segmento Inicial')
                            ->options(StoreTemplateService::options())
                            ->default('none')
                            ->required()
                            ->native(false)
                            ->dehydrated(false),
                    ]),

                Forms\Components\Section::make('Plano & Controle Operacional')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('plan')
                            ->label('Plano')
                            ->options([
                                'starter' => 'Starter',
                                'pro' => 'Profissional',
                                'enterprise' => 'Enterprise',
                            ])
                            ->default('starter')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'active' => 'Ativa',
                                'trial' => 'Período de Teste',
                                'paused' => 'Pausada',
                                'suspended' => 'Suspensa',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Loja Ativa (Acesso Liberado)')
                            ->default(true)
                            ->inline(false),

                        Forms\Components\DateTimePicker::make('trial_ends_at')
                            ->label('Fim do Período de Teste'),

                        Forms\Components\TextInput::make('pause_reason')
                            ->label('Motivo da Pausa / Suspensão')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Domínios & Subdomínios Vinculados')
                    ->description("Se nenhum domínio for adicionado manualmente, será criado automaticamente {$baseDomain}.")
                    ->schema([
                        Forms\Components\Repeater::make('domains')
                            ->relationship()
                            ->label('Domínios')
                            ->schema([
                                Forms\Components\TextInput::make('domain')
                                    ->label('Domínio ou Subdomínio')
                                    ->placeholder("novaempresa.{$baseDomain}")
                                    ->required()
                                    ->distinct()
                                    ->maxLength(255),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Adicionar Domínio'),
                    ]),

                Forms\Components\Section::make('Administrador Inicial da Loja')
                    ->description('Opcional: cria automaticamente o usuário administrador da loja ao cadastrar.')
                    ->columns(3)
                    ->visibleOn('create')
                    ->schema([
                        Forms\Components\TextInput::make('admin_name')
                            ->label('Nome do Administrador')
                            ->maxLength(255)
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('admin_email')
                            ->label('E-mail de Acesso')
                            ->email()
                            ->autocomplete('off')
                            ->maxLength(255)
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('admin_password')
                            ->label('Senha Inicial')
                            ->password()
                            ->autocomplete('new-password')
                            ->revealable()
                            ->minLength(6)
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $baseDomain = config('tenancy.base_domain', 'viasolutions.com.br');

        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount(['products', 'orders', 'users']))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Empresa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Subdomínio')
                    ->formatStateUsing(fn (string $state): string => "{$state}.{$baseDomain}")
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan')
                    ->label('Plano')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'starter' => 'Starter',
                        'pro' => 'Profissional',
                        'enterprise' => 'Enterprise',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'enterprise' => 'warning',
                        'pro' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state, Tenant $record): string => match (true) {
                        ! $record->isActive() && $state === 'suspended' => 'Suspensa',
                        ! $record->isActive() => 'Pausada',
                        $state === 'trial' => 'Teste',
                        default => 'Ativa',
                    })
                    ->color(fn (string $state, Tenant $record): string => match (true) {
                        ! $record->isActive() && $state === 'suspended' => 'danger',
                        ! $record->isActive() => 'warning',
                        $state === 'trial' => 'info',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Produtos')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pedidos')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Usuários')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criada em')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativa',
                        'trial' => 'Teste',
                        'paused' => 'Pausada',
                        'suspended' => 'Suspensa',
                    ]),
                Tables\Filters\SelectFilter::make('plan')
                    ->label('Plano')
                    ->options([
                        'starter' => 'Starter',
                        'pro' => 'Profissional',
                        'enterprise' => 'Enterprise',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('pause')
                    ->label('Pausar')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->visible(fn (Tenant $record): bool => $record->isActive())
                    ->form([
                        Forms\Components\TextInput::make('pause_reason')
                            ->label('Motivo da pausa (opcional)')
                            ->maxLength(255),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Pausar Empresa')
                    ->modalDescription('A loja e o painel administrativo desta empresa ficarão temporariamente indisponíveis.')
                    ->action(function (Tenant $record, array $data): void {
                        $record->pause($data['pause_reason'] ?? null);
                        Notification::make()
                            ->title('Empresa pausada com sucesso')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\Action::make('activate')
                    ->label('Reativar')
                    ->icon('heroicon-o-play-circle')
                    ->color('success')
                    ->visible(fn (Tenant $record): bool => ! $record->isActive())
                    ->requiresConfirmation()
                    ->action(function (Tenant $record): void {
                        $record->activate();
                        Notification::make()
                            ->title('Empresa reativada com sucesso')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
