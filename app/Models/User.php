<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ap_paterno',
        'ap_materno',
        'telefono',
        'departamento',
        'localidad',
        'barrio',
        'ubicacion',
        'latitud',
        'longitud',
        'estado',
        'genero',
        'fecha_nac',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function estadiasEnfermedades()
    {
        return $this->hasMany('App\Models\estadia_enfermedad');
    }

    public function brigadas()
    {
        return $this->belongsToMany('App\Models\brigada');
    }


    public function atenciones_como_medico()
    {
        return $this->hasMany('App\Models\Atencion', 'medico_id');
    }

    public function atenciones_como_paciente()
    {
        return $this->hasMany('App\Models\Atencion', 'paciente_id');
    }

    public function solicitudes()
    {
        return $this->hasMany('App\Models\solicitud', 'user_id');
    }


    public static function scopeListadoGeneral(Builder $query, Request $request = null)
    {
        return $query->when(isset($request->buscador), function ($query) use ($request) {
            $buscador = $request->buscador;
            $query->where(function ($q) use ($buscador) {
                $q->orwhere('ci', 'like', "%$buscador%");
                $q->orWhere('name', 'like', "%$buscador%");
                $q->orWhere('email', 'like', "%$buscador%");
                $q->orWhere('ap_paterno', 'like', "%$buscador%");
                $q->orWhere('ap_materno', 'like', "%$buscador%");
            });
        })->orderBy('name', 'asc')->paginate(10);
    }

    public static function scopeListadoGeneralGet(Builder $query, Request $request = null)
    {
        return $query->when(isset($request->buscador), function ($query) use ($request) {
            $buscador = $request->buscador;
            $query->where(function ($q) use ($buscador) {
                $q->orwhere('ci', 'like', "%$buscador%");
                $q->orWhere('name', 'like', "%$buscador%");
                $q->orWhere('email', 'like', "%$buscador%");
                $q->orWhere('ap_paterno', 'like', "%$buscador%");
                $q->orWhere('ap_materno', 'like', "%$buscador%");
            });
        })->orderBy('name', 'asc')->get();
    }

    public static function scopeListadoByRol(Builder $query, Request $request = null)
    {
        return $query
            ->when(isset($request->buscador), function ($query) use ($request) {
                $buscador = $request->buscador;
                $query->where(function ($q) use ($buscador) {
                    $q->orWhere('ci', 'like', "%$buscador%");
                    $q->orWhere('name', 'like', "%$buscador%");
                    $q->orWhere('email', 'like', "%$buscador%");
                    $q->orWhere('ap_paterno', 'like', "%$buscador%");
                    $q->orWhere('ap_materno', 'like', "%$buscador%");
                });
            })->when(isset($request->filtro_user), function ($query) use ($request) {
                $rol_id = $request->filtro_user;
                $query->whereHas('roles', function ($q) use ($rol_id) {
                    $q->where('id', $rol_id);
                });
            })->orderBy('name', 'asc')
            ->paginate(10);
    }
}
