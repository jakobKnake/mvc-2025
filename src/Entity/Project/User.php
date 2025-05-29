<?php

namespace App\Entity\Project;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    /**
     * User ID.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Username for user.
     * For log in.
     */
    #[ORM\Column(length: 45)]
    private ?string $username = null;

    /**
     * Password for user.
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * User's balance in 'money'.
     */
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $balance = null;

    /**
     * Avatar for user profile.
     */
    #[ORM\Column(length: 255)]
    private ?string $profile_pic = null;

    /**
     * @var Collection<int, History>
     */
    #[ORM\OneToMany(targetEntity: History::class, mappedBy: 'user_id')]
    private Collection $histories;

    /**
     * Automatically initialize $histories when doing new User(). 
     */
    public function __construct()
    {
        $this->histories = new ArrayCollection();
    }

    /**
     * Get the user id.
     * @return int The id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the username from user.
     * @return string The username.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the user's username.
     * @param string $username The username.
     * @return static $this.
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the user's password.
     * @return string The password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the user's password.
     * @param string $password The password.
     * @return static $this.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the user's balance.
     * @return string The balance.
     */
    public function getBalance(): ?string
    {
        return $this->balance;
    }

    /**
     * Set the user's balance.
     * @param string $balance The balance.
     * @return static $this.
     */
    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get the user's profile picture
     * @return string The img file name.
     */
    public function getProfilePic(): ?string
    {
        return $this->profile_pic;
    }

    /**
     * Set the user's profile picture.
     * @param string $profile_pic The name of the img file.
     * @return static $this.
     */
    public function setProfilePic(string $profile_pic): static
    {
        $this->profile_pic = $profile_pic;

        return $this;
    }

    /**
     * Get the history for the user.
     * Like transactions, game wins etc.
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    /**
     * Add new history event.
     * Like new transaction.
     * @param History $history The history.
     * @return static $this.
     */
    public function addHistory(History $history): static
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setUserId($this);
        }

        return $this;
    }

    /**
     * Remove a user history event.
     * @param History $history The history.
     * @return static $this.
     */
    public function removeHistory(History $history): static
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getUserId() === $this) {
                $history->setUserId(null);
            }
        }

        return $this;
    }
}
