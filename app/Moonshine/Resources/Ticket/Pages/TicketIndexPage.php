<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Ticket\Pages;

use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Switcher;
use MoonShine\UI\Components\Metrics\Wrapped\ValueMetric;
use MoonShine\Laravel\QueryTags\QueryTag;

class TicketIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            Text::make('ФИО', 'full_name')
                ->sortable(),
                
            Email::make('Email', 'email')
                ->sortable(),
                
            Text::make('Телефон', 'phone'),
                
            Switcher::make('Оплачено', 'is_paid'),
            
            Text::make('ID платежа', 'payment_id'), // Убрали copyable
                
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
                
            Email::make('Email', 'email')
                ->placeholder('Поиск по email...'),
                
            Switcher::make('Оплачено', 'is_paid'),
            
            Date::make('Дата', 'created_at')
                ->placeholder('Выберите дату'),
        ];
    }

    protected function search(): array
    {
        return ['full_name', 'email', 'phone', 'payment_id'];
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
        ];
    }
}