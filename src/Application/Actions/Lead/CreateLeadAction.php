<?php
namespace App\Application\Actions\Lead;

use App\Domain\Entities\Lead;
use App\Domain\Repositories\LeadRepositoryInterface;
use App\Infrastructure\Persistence\LeadSender;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateLeadAction
{
    private LeadRepositoryInterface $leadRepository;
    private LeadSender $leadSender;

    public function __construct(
        LeadRepositoryInterface $leadRepository,
        LeadSender $leadSender
    ) {
        $this->leadRepository = $leadRepository;
        $this->leadSender = $leadSender;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (empty($data['name']) || strlen($data['name']) < 3 || strlen($data['name']) > 50 ||
            empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            empty($data['source']) || !in_array($data['source'], ['facebook', 'google', 'linkedin', 'manual'])) {
            return $response->withStatus(400)->withJson(['error' => 'Invalid input']);
        }

        $lead = new Lead($data['name'], $data['email'], $data['phone'] ?? null, $data['source']);
        $leadId = $this->leadRepository->save($lead);
        
        try {
            $this->leadSender->send($lead);
            $apiStatus = 'success';
        } catch (\Exception $e) {
            $apiStatus = 'pending';
        }

        $data = [
            'message' => 'Lead created successfully',
            'id' => $leadId,
            'api_status' => $apiStatus
        ];

        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}

