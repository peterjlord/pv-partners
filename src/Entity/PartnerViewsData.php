<?php

namespace Drupal\pvm_partners\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Partner entities.
 */
class PartnerViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
