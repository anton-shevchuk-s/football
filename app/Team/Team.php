<?php
namespace App\Team;

use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    protected $table = 'teams';

    public function get_id(): int {
        return $this->attributes['id'];
    }

    public function get_name(): string {
        return $this->attributes['name'];
    }

    public function get_attack():int {
        return $this->attributes['attack'];
    }

    public function get_defense():int {
        return $this->attributes['defense'];
    }

}
