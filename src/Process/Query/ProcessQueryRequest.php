<?php

namespace Logistio\Symmetry\Process\Query;

use Logistio\Symmetry\Query\Macro\ColumnCode\ApiColumnCodeTag;
use Logistio\Symmetry\Query\Request\QueryRequest;

/**
 * Class ProcessQueryRequest
 * @package Logistio\Symmetry\Process\Query
 */
class ProcessQueryRequest extends QueryRequest
{
    /**
     * @param array $requestData
     * @return ProcessQueryRequest
     * @throws \App\Exceptions\ValidationException
     * @throws \Exception
     */
    public static function make(array $requestData)
    {
        $request = new self();

        $apiColumnCodeTagsArray = ProcessApiColumnCodeConfig::$config;

        $request->setApiColumnCodeTags(ApiColumnCodeTag::makeFromArray($apiColumnCodeTagsArray));

        $decorator = new ProcessQueryRequestDecorator();

        $decorator->decorate($request, $requestData);

        return $request;
    }
}