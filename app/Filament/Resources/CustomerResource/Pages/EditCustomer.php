<?php

declare(strict_types=1);

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('anonymize')
                ->label('Anonimizar (LGPD)')
                ->icon('heroicon-o-shield-exclamation')
                ->color('danger')
                ->visible(fn (Customer $record): bool => ! $record->isAnonymized())
                ->requiresConfirmation()
                ->action(function (Customer $record): void {
                    $record->anonymize();
                    $this->refreshFormData([
                        'name',
                        'email',
                        'phone',
                        'cpf',
                        'default_zipcode',
                        'default_address',
                        'default_number',
                        'default_complement',
                        'default_neighborhood',
                        'default_city',
                        'default_state',
                        'notes',
                        'marketing_opt_in',
                        'anonymized_at',
                    ]);
                    Notification::make()
                        ->title('Dados anonimizados em conformidade com a LGPD.')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
