<?php
namespace PackageFactory\TypedFusion;

final class Position
{
    /**
     * @var int
     */
    private $rowIndex;

    /**
     * @var int
     */
    private $columnIndex;

    /**
     * @param integer $rowIndex
     * @param integer $columnIndex
     */
    private function __construct(
        int $rowIndex,
        int $columnIndex
    ) {
        $this->rowIndex = $rowIndex;
        $this->columnIndex = $columnIndex;
    }

    /**
     * @param integer $rowIndex
     * @param integer $columnIndex
     * @return Position
     */
    public static function create(
        int $rowIndex,
        int $columnIndex
    ): Position {
        return new Position(
            $rowIndex,
            $columnIndex
        );
    }

    /**
     * @return integer
     */
    public function getRowIndex(): int
    {
        return $this->rowIndex;
    }

    /**
     * @return integer
     */
    public function getColumnIndex(): int
    {
        return $this->columnIndex;
    }

    /**
     * @param Position $other
     * @return boolean
     */
    public function equals(Position $other): bool
    {
        return (
            $this->getRowIndex() === $other->getRowIndex() &&
            $this->getColumnIndex() === $other->getColumnIndex()
        );
    }

    /**
     * @param Position $other
     * @return boolean
     */
    public function gt(Position $other): bool
    {
        return (
            $this->getRowIndex() > $other->getRowIndex() ||
            $this->getColumnIndex() > $other->getColumnIndex()
        );
    }

    /**
     * @param Position $other
     * @return boolean
     */
    public function gte(Position $other): bool
    {
        return ($this->gt($other) || $this->equals($other));
    }

    /**
     * @param Position $other
     * @return boolean
     */
    public function lt(Position $other): bool
    {
        return (
            $this->getRowIndex() < $other->getRowIndex() ||
            $this->getColumnIndex() < $other->getColumnIndex()
        );
    }

    /**
     * @param Position $other
     * @return boolean
     */
    public function lte(Position $other): bool
    {
        return ($this->lt($other) || $this->equals($other));
    }
}