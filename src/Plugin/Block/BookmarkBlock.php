<?php

namespace Drupal\bookmarks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManager;

/**
 * Provides a 'BookmarkBlock' block.
 *
 * @Block(
 *  id = "bookmark_block",
 *  admin_label = @Translation("Bookmark block"),
 * )
 */
class BookmarkBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Entity\EntityManager definition.
   *
   * @var Drupal\Core\Entity\EntityManager
   */
  protected $entityManager;
  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
        array $configuration,
        $plugin_id,
        $plugin_definition,
        EntityManager $entity_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $form['bookmark_type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Bookmark type'),
      '#description' => $this->t('Define type of bookmark to save.'),
      '#options' => array('Node' => $this->t('Node'), 'Path' => $this->t('Path')),
      '#default_value' => isset($this->configuration['bookmark_type']) ? $this->configuration['bookmark_type'] : 'Node',
      '#weight' => '0',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['a'] = $form_state->getValue('a');
    $this->configuration['bookmark_type'] = $form_state->getValue('bookmark_type');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];
    $build['#markup'] = "<a class='ll-bookmark'></a>";

    return $build;
  }

}
