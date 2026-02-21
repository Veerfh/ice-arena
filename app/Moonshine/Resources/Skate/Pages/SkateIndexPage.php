<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Skate\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\Laravel\QueryTags\QueryTag;

class SkateIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
                
            Text::make('Модель', 'model')
                ->sortable(),
                
            Text::make('Бренд', 'brand')
                ->sortable(),
                
            Number::make('Размер', 'size')
                ->sortable(),
                
            Number::make('Количество', 'quantity')
                ->sortable()
                ->badge(fn($value) => $value > 0 ? 'green' : 'red'),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('Модель', 'model')
                ->placeholder('Поиск по модели...'),
                
            Text::make('Бренд', 'brand')
                ->placeholder('Поиск по бренду...'),
                
            Number::make('Размер', 'size')
                ->placeholder('Фильтр по размеру'),
                
            Number::make('Количество', 'quantity')
                ->min(0)
                ->placeholder('Минимальное количество'),
        ];
    }

    protected function search(): array
    {
        return ['model', 'brand'];
    }

    protected function queryTags(): array
    {
        return [
            QueryTag::make(
                'Все',
                fn($query) => $query
            ),
            QueryTag::make(
                'В наличии',
                fn($query) => $query->where('quantity', '>', 0)
            ),
            QueryTag::make(
                'Нет в наличии',
                fn($query) => $query->where('quantity', '=', 0)
            ),
        ];
    }
}