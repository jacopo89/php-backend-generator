<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\Model;


class JsonData
{
    const MODEL = "model";
    const LAYOUT = "layout";
    const TITLE = "title";
    const RESOURCE = "resource";

    //RELATIONS

    const REFERENCE = "reference";
    const CARDINALITY = "cardinality";
    const EMBEDDED = "embedded";
    const EMBEDDED_FILE = "embedded-file";
    const MULTIPLE = "multiple";
    const ENUM = "enum";


    const ID = "id";
    const LABEL = "label";
    const TYPE = "type";
    const VALIDATORS = "validators";
    const ERROR_MESSAGES = "errorMessages";


    const RELATION_TYPES = [
        self::REFERENCE,
        self::EMBEDDED
    ];
    const OPTIONTEXT = "optionText";
    const FILTERS = "filters";
    const RESOURCE_NAME = "resourceName";
    const OPTIONS = "options";
    const WRITE = "write";
    const READ = "read";


}