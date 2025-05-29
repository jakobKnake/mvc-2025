<?php

namespace App\Entity\Project;

use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    /**
     * The History ID.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The user ID.
     * To connect correct history for user.
     */
    #[ORM\ManyToOne(inversedBy: 'histories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user_id = null;

    /**
     * The action type.
     * What type of event happened.
     */
    #[ORM\Column(length: 255)]
    private ?string $action_type = null;

    /**
     * Amount in transactions.
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    /**
     * The description of the event(History).
     */
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * The date when the event happened.
     */
    #[ORM\Column]
    private ?\DateTime $created = null;

    /**
     * Get the History ID.
     * @return int The id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user id to connect histories.
     * @return User The Id of the user.
     */
    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    /**
     * Set the User Id.
     * @param User|Null $user_id
     * @return static $this.
     */
    public function setUserId(?user $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the action type.
     * @return string The type of event.
     */
    public function getActionType(): ?string
    {
        return $this->action_type;
    }

    /**
     * Set the action type, the event that happened.
     * @param string $action_type The action.
     * @return static $this.
     */
    public function setActionType(string $action_type): static
    {
        $this->action_type = $action_type;

        return $this;
    }

    /**
     * Get the amount being handled in the event.
     * @return string The amount.
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Set the amount.
     * @param string $amount The amount.
     * @return static $this.
     */
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the description.
     * @return string The description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the description.
     * @param string $description The description.
     * @return static $this.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the creation date.
     * @return \DateTime The date the event was created.
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * Set the creation date.
     * @param \DateTime $created The date of creation.
     * @return static $this.
     */
    public function setCreated(\DateTime $created): static
    {
        $this->created = $created;

        return $this;
    }
}
