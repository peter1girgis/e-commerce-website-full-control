<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeProductsResource\Pages;
use App\Filament\Resources\HomeProductsResource\RelationManagers;
use App\Models\home_products;
use App\Models\HomeProducts;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeProductsResource extends Resource
{
    protected static ?string $model = home_products::class;
    protected static ?string $navigationGroup = 'Website Settings';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(function ($record) {
                        $query = Product::where('is_active', 1);

                        if ($record) {
                            // استبعاد المنتجات الموجودة ماعدا المنتج الحالي
                            $query->whereNotIn('id', home_products::where('id', '!=', $record->id)->pluck('product_id'));
                        } else {
                            $query->whereNotIn('id', home_products::pluck('product_id'));
                        }

                        return $query->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('position')
                    ->label('Position')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\ImageColumn::make('product.images')
                ->label('Image')
                ->circular()
                ->getStateUsing(fn($record) => $record->product && is_array($record->product->images) ? $record->product->images[0] ?? null : null),
                Tables\Columns\TextColumn::make('position')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeProducts::route('/'),
            'create' => Pages\CreateHomeProducts::route('/create'),
            'edit' => Pages\EditHomeProducts::route('/{record}/edit'),
        ];
    }
}
