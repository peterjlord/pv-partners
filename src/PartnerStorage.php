<?php

namespace Drupal\pvm_partners;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class PartnerStorage extends SqlContentEntityStorage implements PartnerStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PartnerInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {partner_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {partner_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PartnerInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {partner_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('partner_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
