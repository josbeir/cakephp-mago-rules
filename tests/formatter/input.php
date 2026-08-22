<?php

declare ( strict_types = 1 );

namespace App\Model\Table;

use Cake\ORM\{Query\SelectQuery, Table};

final class ArticlesTable extends Table
{
    public function findPublished(SelectQuery $query, array $options = []): SelectQuery {
        $limit = (int) $options['limit'];
        if (! $limit) {
            return $query;
        }
        return $query->where(['published' => true])->limit($limit);
    }
}
