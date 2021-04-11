<?php


namespace App\Normalizer;


class DateTimeNormalizer extends \Symfony\Component\Serializer\Normalizer\DateTimeNormalizer
{
    public function __construct(array $defaultContext = [])
    {
        $defaultContext = [DateTimeNormalizer::FORMAT_KEY=> "Y-m-d"];
        parent::__construct($defaultContext);
    }
}