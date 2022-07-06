<?php

namespace App\Imports;

use App\Models\Pokemon\Pokemon;
use App\Models\Pokemon\PokemonType;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PokemonImport implements ToModel, WithHeadingRow, WithUpserts
{
    use Importable;

    const NAME = 'pokemon';
    const DESCRIPTION = 'description';
    const TYPE_ONE = 'type_1';
    const TYPE_TWO = 'type_2';
    const HIT_POINTS = 'hp';
    const ATTACK = 'attack';
    const DEFENSE = 'defense';
    const SPEED = 'speed';
    const SPECIAL = 'special';
    const IMAGE_URL_GIF = 'gif';
    const IMAGE_URL_PNG = 'png';

    const HEADING_ROW = 1;

    const REQUIRED_COLUMNS = [
        self::NAME, self::DESCRIPTION,
        self::TYPE_ONE,
        self::HIT_POINTS, self::ATTACK, self::DEFENSE, self::SPEED, self::SPECIAL,
        self::IMAGE_URL_GIF, self::IMAGE_URL_PNG,
    ];

    private Collection $types;

    public function __construct()
    {
        $this->types = PokemonType::get();
    }

    /**
     * @param array $row
     *
     * @return Pokemon|null
     */
    public function model(array $row)
    {
        $rowKeys = array_keys($row);
        if (count(array_diff(self::REQUIRED_COLUMNS, $rowKeys)) !== 0) {
            return null;
        }

        $pokemon = new Pokemon([
            'name' => $row[self::NAME],
            'description' => $row[self::DESCRIPTION],
            'hit_points' => $row[self::HIT_POINTS],
            'attack' => $row[self::ATTACK],
            'defense' => $row[self::DEFENSE],
            'speed' => $row[self::SPEED],
            'special' => $row[self::SPECIAL],
            'image_url_gif' => $row[self::IMAGE_URL_GIF],
            'image_url_png' => $row[self::IMAGE_URL_PNG],
        ]);
        $pokemon->typeOne()->associate($this->getType($row[self::TYPE_ONE]));
        $pokemon->typeTwo()->associate($this->getType($row[self::TYPE_TWO]));

        return $pokemon;
    }

    private function getType($typeString): ?PokemonType
    {
        if (empty($typeString)) {
            return null;
        }

        $type = $this->types
            ->firstWhere('name', $typeString);
        if (!is_null($type)) {
            return $type;
        }

        $type = PokemonType::create([
            'name' => $typeString,
        ]);
        $this->types->push($type);

        return $type;
    }

    public function headingRow(): int
    {
        return self::HEADING_ROW;
    }

    /**
     * @return string
     */
    public function uniqueBy()
    {
        return 'name';
    }
}
