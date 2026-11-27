<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Histórico de Pedidos';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (Order $record): string => $record->status_label)
                    ->color(fn (string $state): string => match ($state) {
                        'delivered' => 'success',
                        'paid', 'preparing', 'shipped' => 'info',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Pagamento')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pix' => 'Pix',
                        'credit_card' => 'Cartão',
                        'boleto' => 'Boleto',
                        default => strtoupper($state),
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('BRL')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('openOrder')
                    ->label('Abrir Pedido')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
