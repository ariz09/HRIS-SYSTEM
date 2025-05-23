<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompensationPackage extends Model
{
    protected $table = 'compensation_packages';
    protected $fillable = [
        'employee_number',
        'basic_pay',
        'rata',
        'comm_allowance',
        'transpo_allowance',
        'parking_toll_allowance',
        'clothing_allowance',
        'atm_account_number',
        'bank_name',
    ];
}
