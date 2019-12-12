<?php

class Desk {

	private $isBlackMove = false; //т.к. первыми ходят белые

	public function __construct() {

		$GLOBALS['desk_figures'][1][1] = new Rook(false);
		$GLOBALS['desk_figures'][2][1] = new Knight(false);
		$GLOBALS['desk_figures'][3][1] = new Bishop(false);
		$GLOBALS['desk_figures'][4][1] = new Queen(false);
		$GLOBALS['desk_figures'][5][1] = new King(false);
		$GLOBALS['desk_figures'][6][1] = new Bishop(false);
		$GLOBALS['desk_figures'][7][1] = new Knight(false);
		$GLOBALS['desk_figures'][8][1] = new Rook(false);

        $GLOBALS['desk_figures'][1][2] = new Pawn(false);
        $GLOBALS['desk_figures'][2][2] = new Pawn(false);
        $GLOBALS['desk_figures'][3][2] = new Pawn(false);
        $GLOBALS['desk_figures'][4][2] = new Pawn(false);
        $GLOBALS['desk_figures'][5][2] = new Pawn(false);
        $GLOBALS['desk_figures'][6][2] = new Pawn(false);
        $GLOBALS['desk_figures'][7][2] = new Pawn(false);
        $GLOBALS['desk_figures'][8][2] = new Pawn(false);

        $GLOBALS['desk_figures'][1][7] = new Pawn(true);
        $GLOBALS['desk_figures'][2][7] = new Pawn(true);
        $GLOBALS['desk_figures'][3][7] = new Pawn(true);
        $GLOBALS['desk_figures'][4][7] = new Pawn(true);
        $GLOBALS['desk_figures'][5][7] = new Pawn(true);
        $GLOBALS['desk_figures'][6][7] = new Pawn(true);
        $GLOBALS['desk_figures'][7][7] = new Pawn(true);
        $GLOBALS['desk_figures'][8][7] = new Pawn(true);

        $GLOBALS['desk_figures'][1][8] = new Rook(true);
        $GLOBALS['desk_figures'][2][8] = new Knight(true);
        $GLOBALS['desk_figures'][3][8] = new Bishop(true);
        $GLOBALS['desk_figures'][4][8] = new Queen(true);
        $GLOBALS['desk_figures'][5][8] = new King(true);
        $GLOBALS['desk_figures'][6][8] = new Bishop(true);
        $GLOBALS['desk_figures'][7][8] = new Knight(true);
        $GLOBALS['desk_figures'][8][8] = new Rook(true);
	}

    public function move($move): void
	{
		$search  = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
		$replace = array(1 , 2 , 3 , 4 , 5 , 6 , 7 , 8);
		$digitalMove = str_replace($search, $replace, $move);

        if (!preg_match('/^([1-8])(\d)-([1-8])(\d)$/', $digitalMove, $match)) {
            throw new \RuntimeException("Incorrect move");
        }

        $xFrom = $match[1];
        $yFrom = $match[2];
        $xTo   = $match[3];
        $yTo   = $match[4];

		if (isset($GLOBALS['desk_figures'][$xFrom][$yFrom])) {
			if ($GLOBALS['desk_figures'][$xFrom][$yFrom]->isBlack !== $this->isBlackMove)
			{
				throw new \RuntimeException($xFrom.$yFrom.'-'.$xTo.$yTo." - недопустимый ход! Сейчас ход другого игрока!");
			}
			$this->isValidMove($xFrom, $yFrom, $xTo, $yTo);
		}

//		переключаем ожидаемого игрока
		if($this->isBlackMove){
			$this->isBlackMove = false;
		}else{
			$this->isBlackMove = true;
		}
	}

    public function dump() {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 1; $x <= 8; $x++) {
                if (isset($GLOBALS['desk_figures'][$x][$y])) {
                    echo $GLOBALS['desk_figures'][$x][$y];
                } else {
                    echo '- ';
                }
            }
            echo "\n";
        }
        echo "  a b c d e f g h\n";
    }

	/**
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 * @return bool
	 */
	public function isValidMove($xFrom, $yFrom, $xTo, $yTo): ?bool
	{
		$figureName = get_class($GLOBALS['desk_figures'][$xFrom][$yFrom]);

		// В теукщей реализации, правила описаны только для пешки - PAWN
		switch ($figureName){
			case "Pawn":
				if ($GLOBALS['desk_figures'][$xFrom][$yFrom]->isValidMove(
					(int)$xFrom, (int)$yFrom, (int)$xTo, (int)$yTo, $GLOBALS['desk_figures']
					)
				)
				{
					return true;
				}
				throw new \RuntimeException($xFrom.$yFrom.'-'.$xTo.$yTo." - недопустимый ход! Пешка не может так пойти!");

			default:
				self::submitMove($xFrom, $yFrom, $xTo, $yTo);
				return true;
		}
	}


	/**
	 * @param $xFrom
	 * @param $yFrom
	 * @param $xTo
	 * @param $yTo
	 */
	public static function submitMove($xFrom, $yFrom, $xTo, $yTo): void
	{
		$GLOBALS['desk_figures'][$xTo][$yTo] = $GLOBALS['desk_figures'][$xFrom][$yFrom];
		unset ($GLOBALS['desk_figures'][$xFrom][$yFrom]);

		$replace = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h');
		$search  = array(1 , 2 , 3 , 4 , 5 , 6 , 7 , 8);
		$letterFrom = str_replace($search, $replace, $xFrom);
		$letterTo = str_replace($search, $replace, $xTo);

		echo $letterFrom . $yFrom . '-' . $letterTo . $yTo . ' - OK' . PHP_EOL;
	}
}
