abstract class Vector {
private $vector = null;

public function __construct() {

}

public function setVector($vector) {
$this->vector = $vector;
}

public function setVectorElement($i, $element) {
$this->vector[$i] = $element;
}

public function addVector() {

}

public function addScalar() {

}

public function subVector() {

}

public function subScalar() {

}

public function multVector() {

}

public function multScalar() {

}

public function divVector() {

}

public function divScalar() {

}

public function getLength() {

}

public function normalize() {

}

public function getAngular() {

}

public function multMatrix() {

}

public function divMatrix() {

}
}