<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebsiteSettingResource\Pages;
use App\Filament\Resources\WebsiteSettingResource\RelationManagers;
use App\Models\website_settings;
use App\Models\WebsiteSetting;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WebsiteSettingResource extends Resource
{
    protected static ?string $model = website_settings::class;
    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('main_logo')
                    ->label('Main Logo')
                    ->image()
                    ->directory('logos'),

                Forms\Components\TextInput::make('products_per_page')
                    ->numeric()
                    ->default(12)
                    ->label('Products per Page'),

                Forms\Components\TextInput::make('home_products_count')
                    ->numeric()
                    ->default(8)
                    ->label('Products on Homepage'),
                Forms\Components\TextInput::make('home_brands_count')
                ->numeric()
                ->default(8)
                ->label('Brands on Homepage'),
                Forms\Components\TextInput::make('home_categories_count')
                ->numeric()
                ->default(8)
                ->label('Categories on Homepage'),

                Forms\Components\TextInput::make('home_ads_count')
                    ->numeric()
                    ->default(3)
                    ->label('Ads on Homepage'),

                Forms\Components\TextInput::make('contact_email')
                    ->email()
                    ->label('Contact Email'),

                Forms\Components\TextInput::make('contact_phone')
                    ->label('contact_phone'),
                Forms\Components\TextInput::make('whatsApp')
                    ->label('WhatsApp'),
                Forms\Components\TextInput::make('facebook_link')
                    ->label('Facebook Link'),
                Forms\Components\TextInput::make('twitter_link')
                    ->label('Twitter Link'),

                Forms\Components\Textarea::make('footer_text')
                    ->label('Footer Text')
                    ->rows(3),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_logo')->label('Logo'),
                Tables\Columns\TextColumn::make('products_per_page'),
                Tables\Columns\TextColumn::make('home_products_count')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('home_ads_count')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('contact_email'),
                Tables\Columns\TextColumn::make('contact_phone'),
                Tables\Columns\TextColumn::make('whatsApp')->toggleable(isToggledHiddenByDefault:true),
                Tables\Columns\TextColumn::make('facebook_link'),
                Tables\Columns\TextColumn::make('twitter_link'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->action(function($record,$column){
                        $name = $column->getName();
                        $record->update([
                            $name => !$record->$name
                        ]);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            //
        ];
    }
    // This method is used to get the number of orders for the navigation badge
    // It returns the count of orders in the database.
    // It is used in the navigation menu to show the number of orders.
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    // This method is used to get the color of the navigation badge
    // It returns 'success' if the count of orders is greater than 10, otherwise
    // it returns 'danger'.
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'danger':'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteSettings::route('/'),
            'create' => Pages\CreateWebsiteSetting::route('/create'),
            'edit' => Pages\EditWebsiteSetting::route('/{record}/edit'),
        ];
    }
}
