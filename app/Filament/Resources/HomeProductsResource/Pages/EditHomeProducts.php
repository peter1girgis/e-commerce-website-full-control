<?php

namespace App\Filament\Resources\HomeProductsResource\Pages;

use App\Filament\Resources\HomeProductsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeProducts extends EditRecord
{
    protected static string $resource = HomeProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
