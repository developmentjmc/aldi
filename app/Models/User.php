<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rules\Password as RulesPassword;
use jeemce\helpers\ArrayHelper;
use jeemce\models\CrudTrait;
use jeemce\models\MainTrait;
use jeemce\models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CrudTrait, MainTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_role',
        'name',
        'username',
        'email',
        'phone',
        'password',
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
        ];
    }

    public function rules($scenario = null)
    {
        $passwordRule = array_merge(
            $this->exists
                ? ['sometimes', 'nullable']
                : ['required'],
            [
                'confirmed',
                RulesPassword::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ]
        );
        $scenarios = [
            null => [
                'username' => ['required', 'min:6', 'regex:/^[a-z0-9]+$/', 'unique:users,username,' . ($this->exists ? $this->id : 'NULL') . ',id'],
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users,email,' . ($this->exists ? $this->id : 'NULL') . ',id'],
                'phone' => ['required', 'unique:users,phone,' . ($this->exists ? $this->id : 'NULL') . ',id'],
                'password' => $passwordRule,
                'password_confirmation' => $this->exists ? ['sometimes', 'nullable'] : ['required'],
            ],
        ];

        $rules = $scenarios[$scenario] ?? $scenarios[null];
        return $rules;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    public static function statusOptions($id = null)
	{
		$options = ArrayHelper::assoc([
			'active' => 'Aktif',
			'inactive' => 'Tidak Aktif',
		], 'ucfirst');
		if ($id) {
			return ($options[$id] ?? $id);
		}
		return $options;
	}
}
