<?php

namespace Drupal\bookmarks;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bookmark entities.
 *
 * @ingroup bookmarks
 */
interface BookmarkInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Bookmark name.
   *
   * @return string
   *   Name of the Bookmark.
   */
  public function getName();

  /**
   * Sets the Bookmark name.
   *
   * @param string $name
   *   The Bookmark name.
   *
   * @return \Drupal\bookmarks\BookmarkInterface
   *   The called Bookmark entity.
   */
  public function setName($name);

  /**
   * Gets the Bookmark creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bookmark.
   */
  public function getCreatedTime();

  /**
   * Sets the Bookmark creation timestamp.
   *
   * @param int $timestamp
   *   The Bookmark creation timestamp.
   *
   * @return \Drupal\bookmarks\BookmarkInterface
   *   The called Bookmark entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bookmark published status indicator.
   *
   * Unpublished Bookmark are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bookmark is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bookmark.
   *
   * @param bool $published
   *   TRUE to set this Bookmark to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bookmarks\BookmarkInterface
   *   The called Bookmark entity.
   */
  public function setPublished($published);

}
