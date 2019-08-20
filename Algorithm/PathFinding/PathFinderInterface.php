
interface PathFinderInterface {
    public static function findPath(
        int $startX, int $startY,
        int $endX, int $endY, 
        Grid $grid, 
        int $heuristic, int $movement, 
    ) : array;
}
