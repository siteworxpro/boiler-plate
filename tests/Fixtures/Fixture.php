<?php declare(strict_types=1);

namespace Tests\Fixtures;


use App\Library\OAuth\Entities\Client;
use App\Models\Model;

/**
 * Class Fixture
 * @package Tests\Fixtures
 */
abstract class Fixture implements FixtureInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Fixture constructor.
     * @param Model|null $model
     */
    protected function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    /**
     * @param Model $model
     */
    final protected function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * @return Model|Client
     */
    final public function getModel(): Model
    {
        return $this->model;
    }
}
