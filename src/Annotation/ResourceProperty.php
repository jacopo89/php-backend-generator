<?php

namespace App\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class ResourceProperty implements Annotation
{
    /**
     * @var string
     */
    public string $targetClass;

    /**
     * @var string
     */
    public string $optionsName;

    /**
     * @var string
     * @required
     * @Enum({"id","reference", "text", "enum", "embedded_single", "embedded_multiple", "file_single", "file_multiple", "string", "boolean", "date", "integer", "float", "array", "phone", "money"})
     */
    public string $type;

    /**
     * @var boolean
     */
    public bool $nullable = false;

    public function toJson(){
        return (array) $this;
    }

}
