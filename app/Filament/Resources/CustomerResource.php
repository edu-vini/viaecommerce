<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Vendas & Pedidos';

    protected static ?string $modelLabel = 'Cliente';

    protected static ?string $pluralModelLabel = 'Clientes & CRM';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados Cadastrais do Cliente')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome Completo')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Telefone / WhatsApp')
                            ->maxLength(30),

                        Forms\Components\TextInput::make('cpf')
                            ->label('CPF')
                            ->maxLength(20),
                    ]),

                Forms\Components\Section::make('Endereço Padrão de Entrega')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('default_zipcode')
                            ->label('CEP')
                            ->maxLength(15),

                        Forms\Components\TextInput::make('default_address')
                            ->label('Logradouro')
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('default_number')
                            ->label('Número')
                            ->maxLength(30),

                        Forms\Components\TextInput::make('default_complement')
                            ->label('Complemento')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('default_neighborhood')
                            ->label('Bairro')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('default_city')
                            ->label('Cidade')
                            ->maxLength(100)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('default_state')
                            ->label('UF')
                            ->maxLength(2),
                    ]),

                Forms\Components\Section::make('CRM & Conformidade LGPD')
                    ->description('Controle de consentimento, observações internas e status de anonimização (Lei nº 13.709/2018).')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Toggle::make('marketing_opt_in')
                            ->label('Aceita Comunicações (Opt-in)')
                            ->inline(false),

                        Forms\Components\DateTimePicker::make('lgpd_consent_at')
                            ->label('Data do Consentimento LGPD'),

                        Forms\Components\DateTimePicker::make('anonymized_at')
                            ->label('Anonimizado em')
                            ->disabled(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Observações CRM')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withCount('orders')
                ->withSum('orders', 'total'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Pedidos')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orders_sum_total')
                    ->label('Total Gasto (LTV)')
                    ->money('BRL')
                    ->sortable()
                    ->placeholder('R$ 0,00'),

                Tables\Columns\IconColumn::make('marketing_opt_in')
                    ->label('Opt-in')
                    ->boolean(),

                Tables\Columns\TextColumn::make('anonymized_at')
                    ->label('LGPD')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Anonimizado' : 'Ativo')
                    ->color(fn ($state): string => $state ? 'gray' : 'success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('marketing_opt_in')
                    ->label('Aceita Comunicações (Opt-in)'),

                Tables\Filters\TernaryFilter::make('anonymized_at')
                    ->label('Status LGPD')
                    ->nullable()
                    ->trueLabel('Somente Anonimizados')
                    ->falseLabel('Somente Ativos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('exportLgpd')
                    ->label('Exportar LGPD')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function (Customer $record): StreamedResponse {
                        $payload = json_encode($record->exportLgpdData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        return response()->streamDownload(
                            function () use ($payload): void {
                                echo $payload;
                            },
                            "dados-lgpd-cliente-{$record->id}.json",
                            ['Content-Type' => 'application/json']
                        );
                    }),

                Tables\Actions\Action::make('anonymize')
                    ->label('Anonimizar')
                    ->icon('heroicon-o-shield-exclamation')
                    ->color('danger')
                    ->visible(fn (Customer $record): bool => ! $record->isAnonymized())
                    ->requiresConfirmation()
                    ->modalHeading('Anonimizar Dados Pessoais (LGPD)')
                    ->modalDescription('Esta ação remove nome, e-mail, telefone, CPF e endereço do cliente e de seus pedidos, mantendo apenas os valores fiscais dos pedidos. Essa operação não pode ser desfeita.')
                    ->action(function (Customer $record): void {
                        $record->anonymize();
                        Notification::make()
                            ->title('Dados do cliente anonimizados em conformidade com a LGPD.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
