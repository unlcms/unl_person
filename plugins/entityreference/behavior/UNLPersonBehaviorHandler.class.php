<?php

/**
 * OG behavior handler.
 */
class UNLPersonBehaviorHandler extends EntityReference_BehaviorHandler_Abstract {
  /**
   * Implements EntityReference_BehaviorHandler::schema_alter
   * 
   * Our target_id (UID) is a varchar.  This defaults to an int, so we need to change it.
   * 
   * @param $schema
   * @param $field
   */
  public function schema_alter(&$schema, $field) {
    //The target_id needs to be a varchar
    $schema['columns']['target_id']['type'] = 'varchar';
    $schema['columns']['target_id']['length'] = 255;
    unset($schema['columns']['target_id']['unsigned']);
  }

  /**
   * Implements EntityReference_BehaviorHandler::is_empty_alter
   * 
   * By default, entitiyreference thinks a target_id is empty if it is not numeric.  Because our target_id (UID)
   * is a string, we need to customize this.
   * 
   * @param $empty
   * @param $item
   * @param $field
   */
  public function is_empty_alter(&$empty, $item, $field) {
    $empty = false;
    
    if (empty($item['target_id'])) {
      $empty = true;
    }
    
    if (!is_string($item['target_id'])) {
      $empty = true;
    }
  }
}
