<?php
namespace App\Game;

class GameResult {

    private $home;
    private $away;

    public function __construct(int $home, int $away) {
        $this->home = $home;
        $this->away = $away;
    }

    public function get_home(): int {
        return $this->home;
    }

    public function get_away(): int {
        return $this->away;
    }

    public function __toString() {
        return sprintf('%d:%d', $this->home, $this->away);
    }
}
