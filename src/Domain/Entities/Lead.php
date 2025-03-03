<?php
namespace App\Domain\Entities;

class Lead
{
     private ?int $id = null;
    private string $name;
    private string $email;
    private ?string $phone;
    private string $source;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $name, string $email, ?string $phone, string $source, ?int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->source = $source;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getSource(): string { return $this->source; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'source' => $this->source
        ];
    }
}
