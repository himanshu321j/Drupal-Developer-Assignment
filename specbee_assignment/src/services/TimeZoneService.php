<?php

namespace Drupal\specbee_assignment\services;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Return The Current Date and time with Time Zone.
 */
class TimeZoneService {

  /**
   * {@inheritdoc}
   */
  public function getCurrentTimeZone($zone) {
    $date_and_time = new DrupalDateTime('now', $zone);
    $date_and_time = $date_and_time->format('jS M Y - h:ia');
    return $date_and_time;
  }

}
