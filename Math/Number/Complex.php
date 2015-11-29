class Complex implements Number {
private $real = 0;
private $imaginary = 0;

public function __construct() {}

public function setReal($real) {
$this->real = $real;
}

public function setImaginary($imaginary) {
$this->imaginary = $imaginary;
}
}