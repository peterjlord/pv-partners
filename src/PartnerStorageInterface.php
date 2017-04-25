<?php

namespace Drupal\pvm_partners;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\pvm_partners\Entity\PartnerInterface;

/**
 * Defines the storage handler class for Partner entities.
 *
 * This extends the base storage class, adding required special handling for
 * Partner entities.
 *
 * @ingroup pvm_partners
 */
interface PartnerStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Partner revision IDs for a specific Partner.
   *
   * @param \Drupal\pvm_partners\Entity\PartnerInterface $entity
   *   The Partner entity.
   *
   * @return int[]
   *   Partner revision IDs (in ascending order).
   */
  public function revisionIds(PartnerInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Partner author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Partner revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\pvm_partners\Entity\PartnerInterface $entity
   *   The Partner entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PartnerInterface $entity);

  /**
   * Unsets the language for all Partner with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
