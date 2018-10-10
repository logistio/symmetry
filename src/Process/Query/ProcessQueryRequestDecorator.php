<?php


namespace Logistio\Symmetry\Process\Query;


use Logistio\Symmetry\Query\Request\Factory\QueryRequestDecorator;
use Logistio\Symmetry\Query\Request\QueryRequestInterface;

class ProcessQueryRequestDecorator extends QueryRequestDecorator
{
    /**
     * @param QueryRequestInterface $queryRequest
     * @param array $input
     * @throws \Logistio\Symmetry\Exception\ValidationException
     */
    public function decorate(QueryRequestInterface $queryRequest, array $input)
    {
        $input['api_column_code_tags'] = ProcessApiColumnCodeConfig::$config;

        parent::decorate($queryRequest, $input);
    }
}