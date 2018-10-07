<?php

namespace App\Game;

class ProbabilityGetter {

    public function get(float $m, int $goals):float {
        return ($m ** $goals) / ($this->fact($goals) * exp($m));
    }

    private function fact(int $n): float {
        if ($n <= 0) {
            return 1;
        }
        if ($n <= 2) {
            return $n;
        }
        return $n * $this->fact($n - 1);
    }
}
