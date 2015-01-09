<?php

class AclxBehavior extends ModelBehavior {

/**
 * Maps ACL type options to ACL models
 *
 * @var array
 * @access protected
 */

/**
 * Sets up the configuation for the model, and loads ACL models if they haven't been already
 *
 * @param mixed $config
 */
    function setup(&$model, $config = array()) {

        if (empty($config)) {
            $config = array('Aro');
        }
        elseif (is_string($config)) {
            $config = array($config);
        }

        $this->settings[$model->name]['types'] = $config;

        foreach ($this->settings[$model->name]['types'] as $type)
        {
            if (!ClassRegistry::isKeySet($type)) {
                uses('model' . DS . 'db_acl');
                $object =& new $type();
            } else {
                $object =& ClassRegistry::getObject($type);
            }
            $model->{$type} =& $object;
        }


        if (!method_exists($model, 'parentNode')) {
            trigger_error("Callback parentNode() not defined in {$model->name}", E_USER_WARNING);
        }
    }
/**
 * Retrieves the Aro/Aco node for this model
 *
 * @param mixed $ref
 * @return array
 */
    function node(&$model, $type, $ref = null) {
        if (empty($ref)) {
            $ref = array('model' => $model->name, 'foreign_key' => $model->id);
        }
        return $model->{$type}->node($ref);
    }
/**
 * Creates a new ARO/ACO node bound to this record
 *
 * @param boolean $created True if this is a new record
 */
    function afterSave(&$model, $created) {
        if ($created) {

            foreach ($this->settings[$model->name]['types'] as $type)
            {
                if ($parent = $model->parentNode($type)) {
                    $parent = $this->node($model, $type, $parent);
                } else {
                    $parent = $model->{$type}->node($model->name);
                }
                $parent_id = Set::extract($parent, "0.{$type}.id");

                $model->{$type}->create();
                $model->{$type}->save(array(
                    'parent_id'        => $parent_id,
                    'model'            => $model->name,
                    'foreign_key'    => $model->id,
                    'alias'            => $model->name . "." . $model->id
                ));
            }
        }
    }
/**
 * Destroys the ARO/ACO node bound to the deleted record
 *
 */
    function afterDelete(&$model) {
        foreach ($this->settings[$model->name]['types'] as $type)
        {
            $node = Set::extract($this->node($model, $type), "0.{$type}.id");
            if (!empty($node)) {
                $model->{$type}->delete($node);
            }
        }
    }
}

?>