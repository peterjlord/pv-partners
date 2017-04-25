<?php

namespace Drupal\pvm_partners\Entity;

use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Partner entities.
 *
 * @ingroup pvm_partners
 */
interface PartnerInterface extends RevisionableInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Partner name.
   *
   * @return string
   *   Name of the Partner.
   */
  public function getName();

  /**
   * Sets the Partner name.
   *
   * @param string $name
   *   The Partner name.
   *
   * @return \Drupal\pvm_partners\Entity\PartnerInterface
   *   The called Partner entity.
   */
  public function setName($name);

  /**
   * Gets the Partner creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Partner.
   */
  public function getCreatedTime();

  /**
   * Sets the Partner creation timestamp.
   *
   * @param int $timestamp
   *   The Partner creation timestamp.
   *
   * @return \Drupal\pvm_partners\Entity\PartnerInterface
   *   The called Partner entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Partner published status indicator.
   *
   * Unpublished Partner are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Partner is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Partner.
   *
   * @param bool $published
   *   TRUE to set this Partner to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pvm_partners\Entity\PartnerInterface
   *   The called Partner entity.
   */
  public function setPublished($published);

  /**
   * Gets the Partner revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Partner revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\pvm_partners\Entity\PartnerInterface
   *   The called Partner entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Partner revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Partner revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\pvm_partners\Entity\PartnerInterface
   *   The called Partner entity.
   */
  public function setRevisionUserId($uid);

}
