<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class LeadsSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            ['name' => 'Juan Perez', 'email' => 'juan@example.com', 'phone' => '1234567890', 'source' => 'google'],
            ['name' => 'Maria Gomez', 'email' => 'maria@example.com', 'phone' => '0987654321', 'source' => 'facebook'],
            ['name' => 'Carlos Ramirez', 'email' => 'carlos@example.com', 'phone' => '1112223333', 'source' => 'linkedin'],
            ['name' => 'Ana Lopez', 'email' => 'ana@example.com', 'phone' => '4445556666', 'source' => 'manual']
        ];

        $pdo = $this->getAdapter()->getConnection();

        foreach ($data as $row) {
            $stmt = $pdo->prepare("
                INSERT INTO leads (name, email, phone, source) 
                VALUES (:name, :email, :phone, :source)
                ON DUPLICATE KEY UPDATE 
                    name = VALUES(name), 
                    phone = VALUES(phone), 
                    source = VALUES(source)
            ");
            $stmt->execute($row);
        }
    }
}
