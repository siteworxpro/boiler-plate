<?php declare(strict_types=1);

namespace Tests\Fixtures;

use App\Models\Model;

interface FixtureInterface
{
    /**
     * generate and return test model
     * @param array $params
     * @return self
     */
    public static function generate(array $params = []): self;

    /**
     * @return Model
     */
    public function getModel(): Model;
}
