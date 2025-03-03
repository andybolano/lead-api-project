<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Lead;

interface LeadRepositoryInterface
{
    public function save(Lead $lead): int;

    /**
     * Retorna una lista de todos los leads.
     * @return Lead[]
     */
    public function findAll(): array;
}
