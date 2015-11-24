<?php

abstract class AbstractModelElement {

    /**
     * Convert an entity's property to array
     * @return array
     */
    abstract public function toDbArray();
}
