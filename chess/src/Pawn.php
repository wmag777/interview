<?php

class Pawn extends Figure {
    public function __toString() {
        return $this->isBlack ? '♟' : '♙';
    }

// Описываем возможные варианты хода пешки:
// Пешка может ходить вперёд (по вертикали) на одну клетку;
// Если пешка ещё ни разу не ходила, она может пойти вперёд на две клетки;
// Пешка не может перепрыгивать через другие фигуры;
// Пешка может бить фигуры противника только по диагонали вперёд на одну клетку;
// Также существует взятие на проходе, но им можно пренебречь :)

	/**
	 * Checking Valid Pawn move
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 * @return bool
	 */
	public function isValidMove($xFrom, $yFrom, $xTo, $yTo): bool
	{
		if($this->isOneStepAhead($xFrom, $yFrom, $xTo, $yTo)
			|| $this->isFirstMoveDoubleStep($xFrom, $yFrom, $xTo, $yTo)
			|| $this->isEatMove($xFrom, $yFrom, $xTo, $yTo)
		)
		{
			Desk::submitMove($xFrom, $yFrom, $xTo, $yTo);
			return true;
		}
		return false;
	}

	/**
	 * Explaining how Pawn move ahead
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 * @return bool
	 */
	public function isOneStepAhead($xFrom, $yFrom, $xTo, $yTo): bool
	{
//Черные ходят на 1 клетку вперед
		if($this->isBlack  &&  $yTo === $yFrom-1  &&  $xFrom === $xTo){
			return true;
		}
//Белые ходят на 1 клетку вперед
		if(!$this->isBlack  &&  $yTo === $yFrom+1  &&  $xFrom === $xTo){
			return true;
		}
		return false;
	}

	/**
	 * Explaining how Pawn can make double step move
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 * @return bool
	 */
	public function isFirstMoveDoubleStep($xFrom, $yFrom, $xTo, $yTo): bool
	{
//Пешка должна стоять на своей линии
		if (($this->isBlack && $yFrom === 7) || (!$this->isBlack && $yFrom === 2)){

//Черные делают ход на два квадрата
			if($this->isBlack && $yTo === 5 && $xFrom === $xTo && !$GLOBALS['desk_figures'][$xTo][6]){
				return true;
			}

//Белые делают ход на два квадрата
			if(!$this->isBlack && $yTo === 4 && $xFrom === $xTo && !$GLOBALS['desk_figures'][$xTo][3]){
				return true;
			}
		}
		return false;
	}

	/**
	 * Explaining how Pawn can eat
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 * @return bool
	 */
	public function isEatMove($xFrom, $yFrom, $xTo, $yTo): bool
	{

		if(!$this->isBlack
			&&  $yTo === $yFrom+1  //шаг вперед
			&&  ($xFrom === $xTo+1 || $xFrom === $xTo-1) //сдвиг влево или вправо
			&& $GLOBALS['desk_figures'][$xTo][$yTo]->isBlack //противник в итоговом квадрате
		){
			echo 'Белая пешка взяла черную фигуру '.
				get_class($GLOBALS['desk_figures'][$xFrom][$yFrom]) . PHP_EOL;
			return true;
		}

		if($this->isBlack
			&&  $yTo === $yFrom-1  //шаг вперед
			&&  ($xFrom === $xTo+1 || $xFrom === $xTo-1) //сдвиг влево или вправо
			&& !$GLOBALS['desk_figures'][$xTo][$yTo]->isBlack //противник в итоговом квадрате
		){
			echo 'Черная пешка взяла белую фигуру '.
				get_class($GLOBALS['desk_figures'][$xFrom][$yFrom]) . PHP_EOL;
			return true;
		}
		return false;
	}

}
