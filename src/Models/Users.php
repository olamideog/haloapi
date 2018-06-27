<?php
namespace Api\Models;

use Api\Core\CoreModel as Model;

class Users extends Model{
	protected $table="users";

	protected $primaryKey = 'id';
	
	protected $fillable = ['id', 'start_date', 'end_date', 'first_name', 'last_name', 'email', 'telnumber', 'address1', 'address2', 'city', 'country', 'postcode', 'product_name', 'cost', 'currency', 'transaction_date'];
}