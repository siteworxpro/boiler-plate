<?php declare(strict_types = 1);

namespace App\Models;

use App\Library\App;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\{Builder, Collection, Concerns\QueriesRelationships, Model as ORM};

/**
 * Class Model
 *
 * @property \Carbon\Carbon created_at
 * @property \Carbon\Carbon updated_at
 *
 * @method static Builder           where(\Closure|\string $column, string $operator = null, mixed $value = null, string $boolean = 'and') // @codingStandardsIgnoreLine
 * @method Model|Builder|null       first(array $columns = [])
 * @method static $this|null        find(int|string $id)
 * @method static $this             join(string $table, string $one, string $operator = null, string $two = null, string $type = 'inner', bool $where = false) // @codingStandardsIgnoreLine
 * @method Collection|Builder[]     get(array $columns = array('*'))
 * @method static Builder           orderBy(\string $column, string $direction = 'asc')
 * @method Builder                  forPage(int $page, int $perPage = 15)
 * @method static Builder           whereNotIn(string $column, array $values)
 * @method Builder                  newQuery()
 *
 * @package App\Models
 */
abstract class Model extends ORM
{
    use BuildsQueries;
    use QueriesRelationships;

    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (!ORM::getConnectionResolver() instanceof ConnectionResolver) {
            $dbContainer = new \Illuminate\Container\Container();
            $connFactory = new ConnectionFactory($dbContainer);
            $conn = $connFactory->make(
                App::di()->config->get('settings.db')
            );

            $resolver = new ConnectionResolver();
            $resolver->addConnection('default', $conn);
            $resolver->setDefaultConnection('default');

            ORM::setConnectionResolver($resolver);
        }

        parent::__construct($attributes);
    }
}
