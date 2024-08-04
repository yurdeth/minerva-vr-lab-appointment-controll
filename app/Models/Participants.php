<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Participants extends Model {
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'participants';
    protected $fillable = [
        'number_of_participants',
        'appointment_id',
        'user_id',
    ];

    public function appointment() {
        return $this->belongsTo(Appointments::class);
    }
}
