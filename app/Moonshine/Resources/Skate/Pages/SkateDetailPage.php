<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Skate\Pages;

use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Fields\Date;

class SkateDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            Text::make('Модель', 'model'),
            
            Text::make('Бренд', 'brand'),
            
            Number::make('Размер', 'size'),
            
            Number::make('Количество', 'quantity'),
            
            Date::make('Дата создания', 'created_at')
                ->format('d.m.Y H:i'),
            
            Date::make('Дата обновления', 'updated_at')
                ->format('d.m.Y H:i'),
        ];
    }
}