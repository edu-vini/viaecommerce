<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Vendas & Pedidos';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?string $pluralModelLabel = 'Gestão de Pedidos';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Status & Notificação do Comprador')
                            ->description('Atualize o status do pedido e envie atualizações ao comprador.')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status Atual do Pedido')
                                    ->options([
                                        'pending' => '⏳ Aguardando Pagamento',
                                        'paid' => '✅ Pagamento Confirmado',
                                        'preparing' => '📦 Em Separação & Embalagem',
                                        'shipped' => '🚚 Enviado / Em Trânsito',
                                        'delivered' => '🎉 Entregue ao Destinatário',
                                        'cancelled' => '❌ Cancelado / Estornado',
                                    ])
                                    ->required()
                                    ->reactive(),

                                Forms\Components\TextInput::make('tracking_code')
                                    ->label('Código de Rastreamento (Correios / Transportadora)')
                                    ->placeholder('Ex: BR892183921SP'),

                                Forms\Components\Textarea::make('status_notes')
                                    ->label('Observação de Status (Visível no Rastreamento do Cliente)')
                                    ->rows(3)
                                    ->placeholder('Ex: Pacote postado na agência dos Correios com seguro total.'),

                                Forms\Components\DateTimePicker::make('status_updated_at')
                                    ->label('Data/Hora da Última Atualização de Status')
                                    ->default(now()),
                            ])
                            ->columnSpan(1),

                        Forms\Components\Section::make('Resumo Geral & Pagamento')
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Código do Pedido')
                                    ->disabled(),

                                Forms\Components\Select::make('payment_method')
                                    ->label('Método de Pagamento')
                                    ->options([
                                        'pix' => 'Pix (Instantâneo)',
                                        'credit_card' => 'Cartão de Crédito',
                                        'boleto' => 'Boleto Bancário',
                                    ])
                                    ->disabled(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal (R$)')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->disabled(),

                                Forms\Components\TextInput::make('discount')
                                    ->label('Desconto Cupom (R$)')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->disabled(),

                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Valor do Frete (R$)')
                                    ->numeric()
                                    ->prefix('R$'),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total Final (R$)')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->extraInputAttributes(['class' => 'font-bold text-lg text-brand']),
                            ])
                            ->columns(2)
                            ->columnSpan(2),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Section::make('Dados do Cliente')
                            ->schema([
                                Forms\Components\TextInput::make('customer_name')->label('Nome Completo')->required(),
                                Forms\Components\TextInput::make('customer_email')->label('E-mail')->email()->required(),
                                Forms\Components\TextInput::make('customer_phone')->label('Telefone / WhatsApp')->required(),
                                Forms\Components\TextInput::make('customer_cpf')->label('CPF')->placeholder('000.000.000-00'),
                            ])->columns(2),

                        Forms\Components\Section::make('Endereço de Entrega')
                            ->schema([
                                Forms\Components\TextInput::make('shipping_zipcode')->label('CEP')->required(),
                                Forms\Components\TextInput::make('shipping_address')->label('Logradouro / Rua')->required(),
                                Forms\Components\TextInput::make('shipping_number')->label('Número')->required(),
                                Forms\Components\TextInput::make('shipping_complement')->label('Complemento'),
                                Forms\Components\TextInput::make('shipping_neighborhood')->label('Bairro')->required(),
                                Forms\Components\TextInput::make('shipping_city')->label('Cidade')->required(),
                                Forms\Components\TextInput::make('shipping_state')->label('UF')->maxLength(2)->required(),
                                Forms\Components\TextInput::make('shipping_service')->label('Modalidade de Frete')->default('SEDEX'),
                            ])->columns(2),
                    ]),

                Forms\Components\Section::make('Itens do Pedido')
                    ->description('Produtos adquiridos pelo cliente nesta compra.')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\TextInput::make('product_title')
                                    ->label('Produto')
                                    ->required()
                                    ->columnSpan(3),

                                Forms\Components\TextInput::make('variation')
                                    ->label('Grade / Variação')
                                    ->placeholder('Tamanho: M | Cor: Off-White')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('price')
                                    ->label('Preço Unit. (R$)')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qtd')
                                    ->numeric()
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('total')
                                    ->label('Total Item (R$)')
                                    ->numeric()
                                    ->prefix('R$')
                                    ->columnSpan(1),
                            ])
                            ->columns(9)
                            ->disableItemCreation()
                            ->disableItemDeletion()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data / Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->description(fn (Order $record): string => "{$record->customer_email} • {$record->customer_phone}"),

                Tables\Columns\TextColumn::make('shipping_city')
                    ->label('Destino')
                    ->formatStateUsing(fn ($state, Order $record) => "{$record->shipping_city}/{$record->shipping_state}")
                    ->description(fn (Order $record) => $record->shipping_service),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status do Pedido')
                    ->badge()
                    ->formatStateUsing(fn (string $state, Order $record): string => $record->status_label)
                    ->color(fn (Order $record): string => $record->status_color)
                    ->sortable(),

                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Rastreio')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Sem rastreio')
                    ->copyable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Aguardando Pagamento',
                        'paid' => 'Pagamento Confirmado',
                        'preparing' => 'Em Separação',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregue',
                        'cancelled' => 'Cancelado',
                    ]),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Forma de Pagamento')
                    ->options([
                        'pix' => 'Pix',
                        'credit_card' => 'Cartão de Crédito',
                        'boleto' => 'Boleto',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('update_status')
                    ->label('Atualizar Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Novo Status do Pedido')
                            ->options([
                                'pending' => '⏳ Aguardando Pagamento',
                                'paid' => '✅ Pagamento Confirmado',
                                'preparing' => '📦 Em Separação & Embalagem',
                                'shipped' => '🚚 Enviado / Em Trânsito',
                                'delivered' => '🎉 Entregue ao Destinatário',
                                'cancelled' => '❌ Cancelado / Estornado',
                            ])
                            ->default(fn (Order $record) => $record->status)
                            ->required(),

                        Forms\Components\TextInput::make('tracking_code')
                            ->label('Código de Rastreio (opcional)')
                            ->placeholder('Ex: BR892183921SP')
                            ->default(fn (Order $record) => $record->tracking_code),

                        Forms\Components\Textarea::make('status_notes')
                            ->label('Mensagem / Observação para o Comprador')
                            ->placeholder('Ex: Pedido despachado via SEDEX. Previsão de entrega em 2 dias úteis.')
                            ->default(fn (Order $record) => $record->status_notes),

                        Forms\Components\Toggle::make('notify_customer')
                            ->label('Disparar Notificação ao Comprador (E-mail / WhatsApp)')
                            ->default(true)
                            ->helperText('Envia aviso formal com o novo status e código de rastreamento.'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'tracking_code' => $data['tracking_code'] ?? $record->tracking_code,
                            'status_notes' => $data['status_notes'] ?? $record->status_notes,
                            'status_updated_at' => now(),
                        ]);

                        $statusLabel = $record->status_label;
                        $notifyMsg = $data['notify_customer']
                            ? " e notificação enviada para {$record->customer_email}!"
                            : '.';

                        Notification::make()
                            ->title('Status Atualizado com Sucesso')
                            ->body("Pedido {$record->code} agora está '{$statusLabel}'{$notifyMsg}")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('track_customer')
                    ->label('Página do Cliente')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Order $record): string => route('order.show', ['code' => $record->code]), shouldOpenInNewTab: true),

                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
