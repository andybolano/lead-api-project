<?php
namespace App\Infrastructure\Persistence;

use App\Domain\Entities\Lead;
use App\Domain\Repositories\LeadRepositoryInterface;
use PDO;

class LeadRepository implements LeadRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Lead $lead): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO leads (name, email, phone, source) VALUES (:name, :email, :phone, :source)");
        $stmt->execute([
            ':name' => $lead->getName(),
            ':email' => $lead->getEmail(),
            ':phone' => $lead->getPhone(),
            ':source' => $lead->getSource()
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM leads");
        $leads = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $leads[] = [
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'] ?? null,
                'source' => $row['source'],
                'created_at' => $row['created_at']
            ];
        }

        return $leads;
    }

}
