<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Booking;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\MoonShine\Resources\Booking\Pages\BookingIndexPage;
use App\MoonShine\Resources\Booking\Pages\BookingDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Booking, BookingIndexPage, BookingFormPage, BookingDetailPage>
 */
class BookingResource extends ModelResource
{
    protected string $model = Booking::class;

    protected string $title = 'Брони коньков';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            BookingIndexPage::class,
            BookingDetailPage::class,
        ];
    }
}
