<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Lead;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class LeadSender
{
    private Client $client;
    private LoggerInterface $logger;
    private string $externalApiUrl;

    public function __construct(Client $client, LoggerInterface $logger, string $externalApiUrl)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->externalApiUrl = $externalApiUrl;
    }

    public function send(Lead $lead): void
    {
        $maxRetries = 3;
        $retryDelay = 2; // seconds
        $attempt = 1;

        // Opciones de la petición
        $options = [
            'json' => $lead->toArray(),
            'connect_timeout' => 5,
            'timeout' => 10,
            'http_errors' => true,
            'verify' => false,
        ];

        while ($attempt <= $maxRetries) {
            try {
                $response = $this->client->post($this->externalApiUrl, $options);
                
                if ($response->getStatusCode() === 200) {
                    $this->logger->info('Lead sent successfully', [
                        'lead_id' => $lead->getId(),
                        'attempt' => $attempt
                    ]);
                    return;
                }

                if ($attempt === $maxRetries) {
                    $this->logger->error('Failed to send lead to external API after ' . $maxRetries . ' attempts', [
                        'lead_id' => $lead->getId(),
                        'status_code' => $response->getStatusCode(),
                        'response_body' => (string) $response->getBody(),
                        'attempts' => $attempt
                    ]);
                    throw new \Exception('Failed to send lead to external API after ' . $maxRetries . ' attempts');
                }

                $this->logger->warning('Failed to send lead to external API, retrying...', [
                    'lead_id' => $lead->getId(),
                    'status_code' => $response->getStatusCode(),
                    'attempt' => $attempt,
                    'max_retries' => $maxRetries
                ]);

                sleep($retryDelay);
                $attempt++;

            } catch (ConnectException $e) {
                $this->logger->warning('Connection error while sending lead to external API', [
                    'lead_id' => $lead->getId(),
                    'attempt' => $attempt,
                    'error' => $e->getMessage()
                ]);

                if ($attempt === $maxRetries) {
                    $this->logger->error('Failed to connect to external API after ' . $maxRetries . ' attempts', [
                        'lead_id' => $lead->getId(),
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }

                sleep($retryDelay);
                $attempt++;

            } catch (RequestException $e) {
                $this->logger->warning('Request error while sending lead to external API', [
                    'lead_id' => $lead->getId(),
                    'attempt' => $attempt,
                    'error' => $e->getMessage()
                ]);

                if ($attempt === $maxRetries) {
                    $this->logger->error('Request failed after ' . $maxRetries . ' attempts', [
                        'lead_id' => $lead->getId(),
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }

                sleep($retryDelay);
                $attempt++;
            }
        }
    }
}