<?php

namespace Drupal\bookmarks;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Bookmark entities.
 *
 * @ingroup bookmarks
 */
class BookmarkListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Bookmark ID');
    $header['name'] = $this->t('Name');
    $header['user'] = $this->t('User');
    $header['article'] = $this->t('Article bookmarked');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bookmarks\Entity\Bookmark */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.bookmark.edit_form', array(
          'bookmark' => $entity->id(),
        )
      )
    );
    $row['user'] = $entity->getOwnerId();
    $row['article'] = $entity->getNodeIdBookmarked();
    return $row + parent::buildRow($entity);
  }

}
