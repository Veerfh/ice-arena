<?php

namespace App\MoonShine\Resources;

use App\Models\Booking;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Boolean;
use MoonShine\Fields\BelongsTo;

class BookingResource extends Resource
{
    public static string $model = Booking::class;

    public static string $title = 'Бронирования';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('ФИО', 'full_name'),
            Text::make('Телефон', 'phone'),
            Number::make('Часов', 'hours'),
            BelongsTo::make('Коньки', 'skate', 'model'),
            Number::make('Размер коньков', 'skate_size'),
            Number::make('Сумма', 'total_amount'),
            Boolean::make('Оплачено', 'is_paid'),
            Text::make('ID платежа', 'payment_id'),
        ];
    }
}