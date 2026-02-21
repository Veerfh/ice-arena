<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Booking\Pages;

use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Date;
use MoonShine\UI\Fields\Switcher;

class BookingDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            Text::make('ФИО', 'full_name'),
            
            Text::make('Телефон', 'phone'),
            
            Number::make('Часов', 'hours'),
            
            Text::make('Коньки', 'skate.model'),
            
            Number::make('Размер коньков', 'skate_size'),
            
            Number::make('Сумма', 'total_amount'),
            
            Switcher::make('Оплачено', 'is_paid'),
            
            Text::make('ID платежа', 'payment_id'),
            
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