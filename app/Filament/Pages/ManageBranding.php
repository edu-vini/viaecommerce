<?php

namespace App\Filament\Pages;

use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageBranding extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Personalização & Loja';

    protected static ?string $navigationLabel = 'Identidade Visual & Cores';

    protected static ?string $title = 'Customização Visual da Loja (Branding)';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-branding';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = StoreSetting::current();
        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('1. Logotipo & Favicon')
                    ->description('Faça o upload da identidade gráfica da sua marca.')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Logotipo Principal')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->imagePreviewHeight('120')
                            ->helperText('Exibido no cabeçalho e rodapé da loja. Formatos recomendados: PNG transparente ou SVG.'),

                        Forms\Components\FileUpload::make('favicon_path')
                            ->label('Ícone da Aba do Navegador (Favicon)')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->imagePreviewHeight('80')
                            ->helperText('Exibido na guia do navegador (PNG quadrado 32x32 ou 64x64).'),
                    ])->columns(2),

                Forms\Components\Section::make('2. Informações Gerais da Loja')
                    ->description('Nome, slogan e dados institucionais que aparecem na loja.')
                    ->schema([
                        Forms\Components\TextInput::make('store_name')
                            ->label('Nome Oficial da Loja')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('slogan')
                            ->label('Slogan / Subtítulo Institucional')
                            ->placeholder('Ex: Moderna. Elegante. Modesta.')
                            ->maxLength(200),
                    ])->columns(2),

                Forms\Components\Section::make('3. Dados de Contato & Localização')
                    ->description('Informações de atendimento ao cliente exibidas no topo, rodapé e sacola.')
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->label('E-mail de Atendimento')
                            ->email()
                            ->required(),

                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Telefone Comercial')
                            ->placeholder('(11) 99842-1200'),

                        Forms\Components\TextInput::make('contact_whatsapp')
                            ->label('WhatsApp (apenas números com DDD)')
                            ->placeholder('5511998421200')
                            ->helperText('Usado no botão flutuante de atendimento.'),

                        Forms\Components\TextInput::make('contact_address')
                            ->label('Endereço Físico / Showroom')
                            ->placeholder('Av. Paulista, 1800 - Jardins, São Paulo - SP'),
                    ])->columns(2),

                Forms\Components\Section::make('4. Redes Sociais da Marca')
                    ->schema([
                        Forms\Components\TextInput::make('social_instagram')
                            ->label('Instagram')
                            ->placeholder('@elisieconcept'),

                        Forms\Components\TextInput::make('social_facebook')
                            ->label('Facebook')
                            ->placeholder('elisieconcept'),

                        Forms\Components\TextInput::make('social_pinterest')
                            ->label('Pinterest')
                            ->placeholder('elisieconcept'),

                        Forms\Components\TextInput::make('social_tiktok')
                            ->label('TikTok')
                            ->placeholder('@elisieconcept'),
                    ])->columns(4),

                Forms\Components\Section::make('5. Paleta de Cores Customizada (Color Pickers)')
                    ->description('Ajuste fino manual de cada variável CSS do design system.')
                    ->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Cor Primária (Brand Primary)')
                            ->required()
                            ->helperText('Botões principais, títulos de destaque e links.'),

                        Forms\Components\ColorPicker::make('primary_hover')
                            ->label('Cor Primária Hover')
                            ->required()
                            ->helperText('Ao passar o mouse nos botões principais.'),

                        Forms\Components\ColorPicker::make('primary_light')
                            ->label('Fundo Suave / Highlight')
                            ->required()
                            ->helperText('Destaques leves e tags.'),

                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('Cor Secundária')
                            ->required()
                            ->helperText('Textos escuros e topo contrastante.'),

                        Forms\Components\ColorPicker::make('accent_color')
                            ->label('Cor de Acento (Accent / Dourado)')
                            ->required()
                            ->helperText('Selos de luxo, badges e promoções.'),

                        Forms\Components\ColorPicker::make('accent_hover')
                            ->label('Cor de Acento Hover')
                            ->required(),

                        Forms\Components\ColorPicker::make('surface_color')
                            ->label('Cor de Superfície (Background Geral)')
                            ->required()
                            ->helperText('Fundo geral da loja e dos cards.'),

                        Forms\Components\ColorPicker::make('border_color')
                            ->label('Cor de Bordas')
                            ->required()
                            ->helperText('Linhas divisórias e contornos refinados.'),
                    ])->columns(4),
            ])
            ->statePath('data');
    }

    public function applyPreset(string $presetKey): void
    {
        $presets = StoreSetting::presets();

        if (! isset($presets[$presetKey])) {
            return;
        }

        $preset = $presets[$presetKey];

        $this->data['active_theme'] = $presetKey;
        $this->data['primary_color'] = $preset['primary_color'];
        $this->data['primary_hover'] = $preset['primary_hover'];
        $this->data['primary_light'] = $preset['primary_light'];
        $this->data['secondary_color'] = $preset['secondary_color'];
        $this->data['accent_color'] = $preset['accent_color'];
        $this->data['accent_hover'] = $preset['accent_hover'];
        $this->data['surface_color'] = $preset['surface_color'];
        $this->data['border_color'] = $preset['border_color'];

        $this->form->fill($this->data);

        Notification::make()
            ->title('Paleta "'.$preset['name'].'" Aplicada!')
            ->body('Os valores dos Color Pickers foram atualizados. Clique em "Salvar Alterações" para aplicar à loja.')
            ->info()
            ->send();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = StoreSetting::current();
        $settings->update($data);

        Notification::make()
            ->title('Identidade Visual Atualizada com Sucesso!')
            ->body('As alterações no branding, logotipo e paleta de cores já estão ativas na loja.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_store')
                ->label('Ver Loja Online')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/'), shouldOpenInNewTab: true),
        ];
    }
}
