<?php

namespace Drupal\bookmarks\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Bookmark edit forms.
 *
 * @ingroup bookmarks
 */
class BookmarkForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\bookmarks\Entity\Bookmark */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bookmark.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bookmark.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bookmark.canonical', ['bookmark' => $entity->id()]);
  }

}
