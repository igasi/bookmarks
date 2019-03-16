<?php

namespace Drupal\bookmarks\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Bookmark entities.
 */
class BookmarkViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['bookmark']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Bookmark'),
      'help' => $this->t('The Bookmark ID.'),
    );

    return $data;
  }

}
