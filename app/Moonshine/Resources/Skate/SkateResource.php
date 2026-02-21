<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Skate;

use Illuminate\Database\Eloquent\Model;
use App\Models\Skate;
use App\MoonShine\Resources\Skate\Pages\SkateIndexPage;
use App\MoonShine\Resources\Skate\Pages\SkateFormPage;
use App\MoonShine\Resources\Skate\Pages\SkateDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Skate, SkateIndexPage, SkateFormPage, SkateDetailPage>
 */
class SkateResource extends ModelResource
{
    protected string $model = Skate::class;

    protected string $title = 'Коньки';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            SkateIndexPage::class,
            SkateFormPage::class,
            SkateDetailPage::class,
        ];
    }
}
