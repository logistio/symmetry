<?php


namespace Logistio\Symmetry\Exception;

interface HttpableException
{
    public function getHttpStatusCode();

    public function toJsonResponse();

    public function toArray();
}