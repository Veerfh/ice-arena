<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use App\MoonShine\Resources\Ticket\Pages\TicketIndexPage;
use App\MoonShine\Resources\Ticket\Pages\TicketDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;

/**
 * @extends ModelResource<Ticket, TicketIndexPage, TicketFormPage, TicketDetailPage>
 */
class TicketResource extends ModelResource
{
    protected string $model = Ticket::class;

    protected string $title = 'Билеты';
    
    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TicketIndexPage::class,
            TicketDetailPage::class,
        ];
    }
}
