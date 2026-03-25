<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const MEMBERSHIP_LEVEL_RULES = [
        'bronze' => [
            'min_spent' => 0,
            'discount_percent' => 5,
        ],
        'silver' => [
            'min_spent' => 20000,
            'discount_percent' => 10,
        ],
        'gold' => [
            'min_spent' => 50000,
            'discount_percent' => 15,
        ],
        'platinum' => [
            'min_spent' => 100000,
            'discount_percent' => 20,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_club_member',
        'membership_joined_at',
        'membership_level',
        'membership_discount_percent',
        'membership_total_spent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_club_member' => 'boolean',
            'membership_joined_at' => 'datetime',
            'membership_discount_percent' => 'integer',
            'membership_total_spent' => 'integer',
        ];
    }

    public static function membershipLevelRules(): array
    {
        return self::MEMBERSHIP_LEVEL_RULES;
    }

    public static function initialMembershipAttributes(): array
    {
        $defaultLevel = array_key_first(self::MEMBERSHIP_LEVEL_RULES);
        $defaultRule = self::MEMBERSHIP_LEVEL_RULES[$defaultLevel];

        return [
            'membership_level' => $defaultLevel,
            'membership_discount_percent' => $defaultRule['discount_percent'],
            'membership_total_spent' => 0,
        ];
    }

    public static function membershipLevelsForDisplay(): array
    {
        $levels = [];

        foreach (self::MEMBERSHIP_LEVEL_RULES as $level => $rule) {
            $levels[] = [
                'level' => $level,
                'discount_percent' => $rule['discount_percent'],
            ];
        }

        return $levels;
    }

    public function recalculateMembershipLevel(): void
    {
        if (! $this->is_club_member) {
            $this->forceFill([
                'membership_level' => null,
                'membership_discount_percent' => 0,
            ]);

            return;
        }

        $totalSpent = (int) $this->membership_total_spent;
        $resolvedLevel = null;
        $resolvedDiscount = 0;

        foreach (self::MEMBERSHIP_LEVEL_RULES as $level => $rule) {
            if ($totalSpent >= $rule['min_spent']) {
                $resolvedLevel = $level;
                $resolvedDiscount = $rule['discount_percent'];
            }
        }

        $this->forceFill([
            'membership_level' => $resolvedLevel,
            'membership_discount_percent' => $resolvedDiscount,
        ]);
    }

    public function discountedAmount(int $amount): int
    {
        if (! $this->is_club_member) {
            return $amount;
        }

        $discountPercent = max(0, min(100, (int) $this->membership_discount_percent));
        $discountValue = (int) floor(($amount * $discountPercent) / 100);

        return max(0, $amount - $discountValue);
    }
}
