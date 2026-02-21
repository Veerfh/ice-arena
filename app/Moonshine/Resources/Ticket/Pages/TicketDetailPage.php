<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Ticket\Pages;

use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Email;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Switcher;

class TicketDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            Text::make('ФИО', 'full_name'),
            
            Email::make('Email', 'email'),
            
            Text::make('Телефон', 'phone'),
            
            Switcher::make('Оплачено', 'is_paid'),
            
            Text::make('ID платежа', 'payment_id'), // Убрали copyable
            
            Text::make('Статус платежа', 'payment_status')
                ->badge(fn($value) => match($value) {
                    'succeeded' => 'green',
                    'pending' => 'yellow',
                    'canceled' => 'red',
                    default => 'gray'
                }),
            
            Date::make('Создано', 'created_at')
                ->format('d.m.Y H:i'),
            
            Date::make('Обновлено', 'updated_at')
                ->format('d.m.Y H:i'),
        ];
    }
}