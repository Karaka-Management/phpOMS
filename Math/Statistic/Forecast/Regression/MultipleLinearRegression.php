<?php

namespace phpOMS\Math\Statistic\Forecast\Regression;

class MultipleLinearRegression {
	public static function getRegression(array $x, array $y) : array {
		$X = new Matrix(count($x), count($x[0]));
		$X->setArray($x);
		$XT = $X->transpose();

		$Y = new Matrix(count($y));
		$Y->setArray($y);
		

		return $XT->mult($X)->inverse()->mult($XT)->mult($Y)->toArray();
	}

	public static function getVariance() : float {}

	public static function getPredictionInterval() : array {}
}