<?php

namespace Drupal\unl_person\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the UnlPerson entity.
 *
 * @ingroup unl_person
 *
 * @ContentEntityType(
 *   id = "unl_person",
 *   label = @Translation("UNL Person entity"),
 *   entity_keys = {
 *     "id" = "uid",
 *     "label" = "label",
 *   },
 *   fieldable = FALSE,
 *   base_table = NULL,
 * )
 */
class UnlPerson extends ContentEntityBase implements ContentEntityInterface {

  /**
   * The base url for the api.  Trailing slash is important.
   *
   * @var string
   */
  public static $api_url = 'https://directory.unl.edu/';

  /**
   * {@inheritdoc}
   */
  public static function load($id) {
    // This function should contain all the code to make a request to the web service and handle any errors.
    $entity = unl_person_load_unl_person($id);

    return $entity;
  }

  /**
   * Search for entities and load them.
   * This simply asks our api for people that match the search term.
   *
   * @param string $term
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
