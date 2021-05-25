<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Shelter;

class ShelterTest extends TestCase
{
    private Shelter $shelter;

    protected function setUp(): void
    {
        $this->shelter = new Shelter();
    }

    public function testSetWidthWithInvalidInputLow(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $this->shelter->setWidth('0');
    }

    public function testSetWidthWithInvalidInputHigh(): void
    {
        $this->expectException(\OutOfRangeException::class);
        $this->shelter->setWidth('100001');
    }

    public function testSetAltitudesWithInvalidInputLow(): void
    {
        $this->shelter->setWidth('1');
        $this->expectException(\OutOfRangeException::class);
        $this->shelter->setAltitudes('-1');
    }

    public function testSetAltitudesWithInvalidInputHigh(): void
    {
        $this->shelter->setWidth('1');
        $this->expectException(\OutOfRangeException::class);
        $this->shelter->setAltitudes('100001');
    }

    public function testSetAltitudeWithInputNotMatchingTheWidthLimit(): void
    {
        $this->shelter->setWidth('100000');
        $this->expectException(\InvalidArgumentException::class);
        $this->shelter->setAltitudes('100000 20 0');
    }

    /**
     * @dataProvider mountains
     */
    public function testCompute(string $width, int $hideouts, string $sequence): void
    {
        $this->shelter->setWidth($width);
        $this->shelter->setAltitudes($sequence);

        self::assertEquals($hideouts, $this->shelter->compute());
    }

    public function mountains(): array
    {
        return [
            [
                '5',
                2,
                '18 5 18 19 3',
            ],
            [
                '10',
                8,
                '52767 11952 41172 8957 98011 84149 44899 53747 17568 27784',
            ],
            [
                '10',
                9,
                '99842 23378 50183 8479 75499 80878 21934 73400 83691 22877',
            ],
            [
                '20',
                15,
                '2660 9959 66702 32466 20570 40968 51771 31022 71256 59995 59438 17301 26714 77103 26428 60215 51366 54476 7273 893',
            ],
        ];
    }
}


