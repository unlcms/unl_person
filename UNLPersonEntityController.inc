<?php
class UNLPersonEntityController extends EntityAPIController {

  /**
   * The base url for the api.  Trailing slash is important.
   * 
   * @var string
   */
  public static $api_url = 'http://directory.unl.edu/';

  public function load($ids = array(), $conditions = array()) {
    if (!is_array($ids)) {
      $ids = (array)$ids;
    }
    $entities = array();
    
    // This method takes an array of IDs, but our webservice only supports loading one entity at a time.
    foreach ($ids as $id) {
      // This function should contain all the code to make a request to the web service and handle any errors.
      if ($entity = unl_person_load_unl_person($id)) {
        // Entities must be keyed by entity ID in order for field values to be correctly attached.
        $entities[$entity->uid] = $entity;
      }
    }
    
    return $entities;
  }

  /**
   * Search for entities and load them.
   * This simply asks our api for people that match the search term.
   * 
   * @param $term
   * @return array
   */
  public static function search($term) {
    $url = self::$api_url . '?q=' . urlencode($term) . '&format=json';
    
    $results = array();
    
    if (empty($term)) {
      return $results;
    }
    
    if (!$json = file_get_contents($url)) {
      watchdog('unl_person', 'Unable to get contents at %url', array('%url'=>$url));
      return $results;
    }
    
    if (!$data = json_decode($json, true)) {
      watchdog('unl_person', 'Unable to json_decode for %url', array('%url'=>$url));
      return $results;
    }
    
    foreach ($data as $record) {
      if (!$entity = unl_person_json_to_unl_person($record)) {
        continue;
      }

      $results[$entity->uid] = $entity;
    }
    
    return $results;
  }
}