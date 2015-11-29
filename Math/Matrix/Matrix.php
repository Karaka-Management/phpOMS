abstract class Matrix {
private $matrix = null;

public function __construct() {

}

public function setColumn($i, $vector) {
$this->matrix[$i] = $vector;
}

public function setRow($i, $vector) {
$count = count($vector)-1;

for($c = 0; $c < $count; $c++) {
$this->matrix[$c][$i] = $vector[$c];
}
}
}