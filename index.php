
<?php

class Sudoku {
    const BLOCK_SIZE = 3;
    const SIZE = self::BLOCK_SIZE * self::BLOCK_SIZE;

    protected array $grid;

    public function __construct() {
        $this->init();
    }

    protected function init(): void {
        $this->grid = array_fill(0, self::SIZE, array_fill(0, self::SIZE, 0));
    }

    public function generate(): bool {
        return $this->solve(0, 0);
    }

    protected function solve(int $row, int $col): bool {
        if ($row == self::SIZE) {
            return true; // Completed the grid
        }

        // Move to the next row if at the end of the current row
        $nextRow = ($col == self::SIZE - 1) ? $row + 1 : $row;
        $nextCol = ($col == self::SIZE - 1) ? 0 : $col + 1;

        // Try placing numbers 1 through SIZE
        for ($num = 1; $num <= self::SIZE; $num++) {
            $this->grid[$row][$col] = $num;
            if ($this->isValidcell($row, $col) && $this->solve($nextRow, $nextCol)) {
                return true;
            }
            $this->grid[$row][$col] = 0; // Reset if not valid
        }
        return false; // Backtrack
    }

    protected function isValidcell(int $x, int $y): bool {
        return $this->isValidInRow($x, $y) &&
               $this->isValidInCol($x, $y) &&
               $this->isValidInBlock($x, $y);
    }

    protected function isValidInRow(int $x, int $y): bool {
        $value = $this->grid[$x][$y];
        for ($j = 0; $j < self::SIZE; $j++) {
            if ($j != $y && $this->grid[$x][$j] == $value) {
                return false;
            }
        }
        return true;
    }

    protected function isValidInCol(int $x, int $y): bool {
        $value = $this->grid[$x][$y];
        for ($i = 0; $i < self::SIZE; $i++) {
            if ($i != $x && $this->grid[$i][$y] == $value) {
                return false;
            }
        }
        return true;
    }

    protected function isValidInBlock(int $row, int $col): bool {
        $value = $this->grid[$row][$col];
        $blockStartRow = intdiv($row, self::BLOCK_SIZE) * self::BLOCK_SIZE;
        $blockStartCol = intdiv($col, self::BLOCK_SIZE) * self::BLOCK_SIZE;

        for ($i = $blockStartRow; $i < $blockStartRow + self::BLOCK_SIZE; $i++) {
            for ($j = $blockStartCol; $j < $blockStartCol + self::BLOCK_SIZE; $j++) {
                if (($i != $row || $j != $col) && $this->grid[$i][$j] == $value) {
                    return false;
                }
            }
        }
        return true;
    }

    public function render(): void {
        foreach ($this->grid as $row) {
            echo implode("\t", $row) . PHP_EOL;
        }
    }
}

$sudoku = new Sudoku();
if ($sudoku->generate()) {
    $sudoku->render();
} else {
    echo "Failed to generate Sudoku grid.\n";
}

?>

 

