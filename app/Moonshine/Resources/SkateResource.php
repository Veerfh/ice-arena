<?php

namespace App\MoonShine\Resources;

use App\Models\Skate;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Number;
use MoonShine\Fields\Image;

class SkateResource extends Resource
{
    public static string $model = Skate::class;

    public static string $title = 'Коньки';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Модель', 'model')->required(),
            Text::make('Бренд', 'brand')->required(),
            Number::make('Размер', 'size')->required(),
            Number::make('Количество', 'quantity')->required(),
            Image::make('Изображение', 'image')->disk('public')->dir('skates'),
        ];
    }

    public function rules($item): array
    {
        return [
            'model' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'size' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ];
    }
}