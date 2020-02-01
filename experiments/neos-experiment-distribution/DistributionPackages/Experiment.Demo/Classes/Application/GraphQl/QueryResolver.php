<?php
namespace Experiment\Demo\Application\GraphQl;

use t3n\GraphQL\ResolverInterface;

class QueryResolver implements ResolverInterface
{
    public function ping(): string
    {
        return 'pong';
    }
}