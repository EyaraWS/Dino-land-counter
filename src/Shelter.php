<?php
declare(strict_types=1);

namespace App;

class Shelter
{
    private int $width;
    private array $altitudes;

    /**
     * @throws \OutOfRangeException
     */
    public function setWidth(string $width): void
    {
        if ((int) $width < 1 || (int) $width > 100000) {
            throw new \OutOfRangeException('The width should be between 1 and 100000.');
        }
        $this->width = (int) $width;
    }

    /**
     * @throws \OutOfRangeException
     * @throws \InvalidArgumentException
     */
    public function setAltitudes(string $altitudesStr): void
    {
        $altitudes = explode(' ', $altitudesStr);
        foreach ($altitudes as $altitude) {
            if ((int) $altitude < 0 ||(int) $altitude > 100000) {
                throw new \OutOfRangeException('The altitude should be between 0 and 100000.');
            }
            $this->altitudes[] = (int) $altitude;
        }

        if (count($this->altitudes) !== $this->width) {
            throw new \InvalidArgumentException("You should have exactly $this->width altitudes in your list.");
        }
    }

    public function compute(): int
    {
        $high = $this->altitudes[0];
        $hideouts = 0;
        foreach ($this->altitudes as $altitude) {
            if ($altitude >= $high) {
                $high = $altitude;
                continue;
            }

            $hideouts++;
        }

        return $hideouts;
    }
}
