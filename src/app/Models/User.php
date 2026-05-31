<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'role', 'newsletter'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'newsletter'        => 'boolean',
        ];
    }

    public function donations(): HasMany { return $this->hasMany(Donation::class); }
    public function posts(): HasMany     { return $this->hasMany(Post::class, 'author_id'); }

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isEditor(): bool { return in_array($this->role, ['admin', 'editor'], true); }
}
