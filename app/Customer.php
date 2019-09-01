<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

// Initial Setting Functions

class Customer extends Model {


	protected $table = 'customers';
   protected $fillable = [
        'firstname',  'lastname','email','vault_id'
    ];

}   