<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Skate\Pages;

use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;
use MoonShine\UI\Fields\Number;
use MoonShine\UI\Fields\Image;
use MoonShine\UI\Components\Layout\Flex;

class SkateFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            
            // Поля друг под другом на всю ширину
            Text::make('Модель', 'model')
                ->required()
                ->placeholder('Например: Supreme')
                ->hint('Введите модель коньков')
                ->customAttributes(['style' => 'width: 100%;']),
                
            Text::make('Бренд', 'brand')
                ->required()
                ->placeholder('Например: Bauer')
                ->hint('Производитель коньков')
                ->customAttributes(['style' => 'width: 100%;']),
                
            Number::make('Размер', 'size')
                ->required()
                ->min(26)
                ->max(47)
                ->step(1)
                ->placeholder('Размер от 26 до 47')
                ->hint('Укажите размер')
                ->customAttributes(['style' => 'width: 100%;']),
                
            Number::make('Количество', 'quantity')
                ->required()
                ->min(0)
                ->default(1)
                ->placeholder('Доступное количество')
                ->hint('Сколько пар в наличии')
                ->customAttributes(['style' => 'width: 100%;']),
        ];
    }

    public function rules(DataWrapperContract $item): array
    {
        return [
            'model' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'size' => ['required', 'integer', 'min:26', 'max:47'],
            'quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}