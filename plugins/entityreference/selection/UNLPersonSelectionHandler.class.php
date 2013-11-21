<?php


/**
 * OG selection handler.
 */
class UNLPersonSelectionHandler extends EntityReference_SelectionHandler_Generic {

  /**
   * The target entity type will ALWAYS be unl_person
   * 
   * @var string
   */
  public $target_entity_type = 'unl_person';
  
  /**
   * Implements EntityReferenceHandler::getInstance().
   */
  public static function getInstance($field, $instance = NULL, $entity_type = NULL, $entity = NULL) {
    return new UNLPersonSelectionHandler($field, $instance, $entity_type, $entity);
  }

  /**
   * Override EntityReferenceHandler::settingsForm().
   */
  public static function settingsForm($field, $instance) {
    //Nothing to customize, really.
    $form['selection_handler'] = array(
      '#markup' => t('This will reference the UNL Directory'),
    );
    return $form;
  }

  /**
   * $matches will be a string that describes a selected person, use their full name and label.  Examples include
   * Michael D Fairchild [staff - Web Programmer]
   * or
   * Michael D Fairchild [student]
   * or
   * Michael Fairchild
   * or
   * Fairchild
   * 
   * Implements EntityReferenceHandler::getReferencableEntities().
   */
  public function getReferencableEntities($match = NULL, $match_operator = 'CONTAINS', $limit = 0) {
    $options = array();

    //parse strip details stuff out if present
    $affiliation = false;
    $title = false;
    $uid = false;

    if (preg_match('/\[(?P<affiliation>[a-zA-Z0-9\s]*[^-])(\s-\s(?P<title>.*))?\](\s\((?P<uid>.*)\))?/i', $match, $details)) {
      if (isset($details['affiliation'])) {
        $affiliation = $details['affiliation'];
      }
      if (isset($details['title'])) {
        $title = $details['title'];
      }
      if (isset($details['uid'])) {
        $uid = $details['uid'];
      }
    }
    
    $name = preg_replace('/\s\[(.*)\](\s\(.*\))?/i', '', $match);
    
    $entities = UNLPersonEntityController::search($name);

    foreach ($entities as $entity_id => $entity) {
      //Make sure that the affiliation matches
      if ($affiliation && ($affiliation != unl_person_label_part_sanitize($entity->edu_affiliation))) {
        continue;
      }
      
      //Make sure that the title matches
      if ($title && ($title != unl_person_label_part_sanitize($entity->title))) {
        continue;
      }
      
      if ($uid && ($uid != $entity->uid)) {
        continue;
      }
      
      $options[$this->target_entity_type][$entity_id] = check_plain($entity->label);
    }

    return $options;
  }

  /**
   * Implements EntityReferenceHandler::validateReferencableEntities().
   */
  public function validateReferencableEntities(array $ids) {
    if ($ids && is_array($ids)) {
      return $ids;
    }

    return array();
  }

  /**
   * Implements EntityReferenceHandler::validateAutocompleteInput().
   */
  public function validateAutocompleteInput($input, &$element, &$form_state, $form) {
    
    $entities = $this->getReferencableEntities($input, '=', 6);
    if (empty($entities)) {
      // Error if there are no entities available for a required field.
      form_error($element, t('There are no entities matching "%value"', array('%value' => $input)));
    }
    elseif (count($entities) > 5) {
      // Error if there are more than 5 matching entities.
      form_error($element, t('Many entities are called %value. Specify the one you want by appending the id in parentheses, like "@value (@id)"', array(
        '%value' => $input,
        '@value' => $input,
        '@id' => key($entities),
      )));
    }
    elseif (count($entities) > 1) {
      // More helpful error if there are only a few matching entities.
      $multiples = array();
      foreach ($entities as $id => $name) {
        $multiples[] = $name . ' (' . $id . ')';
      }
      form_error($element, t('Multiple entities match this reference; "%multiple"', array('%multiple' => implode('", "', $multiples))));
    }
    else {
      // Take the one and only matching entity.
      return key($entities[$this->target_entity_type]);
    }
  }
}
