<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Booking\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Date;
use MoonShine\Laravel\Fields\Relationships\BelongsTo;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\Laravel\QueryTags\QueryTag;
use App\MoonShine\Resources\SkateResource;

class BookingIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            Text::make('ФИО', 'full_name')
                ->sortable(),
                
            Text::make('Телефон', 'phone'),
                
            Number::make('Часов', 'hours')
                ->sortable(),
                
            // Исправлено: передаем ресурс правильным способом
            BelongsTo::make('Коньки', 'skate', SkateResource::class)
                ->nullable(),
                
            Number::make('Сумма', 'total_amount')
                ->sortable()
                ->badge('purple'),
                
            Switcher::make('Оплачено', 'is_paid'),
                
            Date::make('Дата', 'created_at')
                ->format('d.m.Y H:i')
                ->sortable(),
        ];
    }

    protected function filters(): iterable
    {
        return [
            Text::make('ФИО', 'full_name')
                ->placeholder('Поиск по ФИО...'),
                
            Switcher::make('Оплачено', 'is_paid'),
            
            Date::make('Дата', 'created_at')
                ->placeholder('Выберите дату'),
        ];
    }

    protected function search(): array
    {
        return ['full_name', 'phone', 'payment_id'];
    }

    protected function queryTags(): array
    {
        return [
            QueryTag::make(
                'Все',
                fn($query) => $query
            ),
            QueryTag::make(
                'Оплаченные',
                fn($query) => $query->where('is_paid', true)
            ),
            QueryTag::make(
                'Неоплаченные',
                fn($query) => $query->where('is_paid', false)
            ),
            QueryTag::make(
                'За сегодня',
                fn($query) => $query->whereDate('created_at', today())
            ),
            QueryTag::make(
                'За эту неделю',
                fn($query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ),
        ];
    }
}