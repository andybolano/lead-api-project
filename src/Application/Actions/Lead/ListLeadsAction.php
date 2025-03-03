<?php

namespace App\Application\Actions\Lead;

use App\Domain\Repositories\LeadRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListLeadsAction
{
    private LeadRepositoryInterface $leadRepository;

    public function __construct(LeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $leads = $this->leadRepository->findAll();

        $response->getBody()->write(json_encode($leads));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
