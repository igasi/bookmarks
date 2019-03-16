<?php

namespace Drupal\bookmarks\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;
use Drupal\Core\Entity\EntityManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\bookmarks\Entity\Bookmark;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "bookmark_rest_resource",
 *   label = @Translation("Bookmark rest resource"),
 *   serialization_class = "Drupal\bookmarks\Entity\Bookmark",
 *   uri_paths = {
 *     "canonical" = "/bookmarks/{bookmarked}",
 *     "https://www.drupal.org/link-relations/create" = "/bookmarks/add"
 *   }
 * )
 */
class BookmarkRestResource extends ResourceBase {

  /**
   * Drupal\Core\Entity\EntityManager definition.
   *
   * @var Drupal\Core\Entity\EntityManager
   */
  protected $storageBookmark;

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    EntityManager $entity_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->storageBookmark = $entity_manager->getStorage('bookmark');

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('bookmarks'),
      $container->get('current_user'),
      $container->get('entity.manager')

    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($article_id = NULL) {

    if ($article_id) {
      $permission = 'view published bookmark entities';
      if(!$this->currentUser->hasPermission($permission)) {
        throw new AccessDeniedHttpException();
      }

      $bookmark_id = \Drupal::entityQuery('bookmark')
          ->condition('user_id',$this->currentUser->id())
          ->condition('nodeid_bookmarked',$article_id)
          ->execute();

      if (is_array($bookmark_id) && count($bookmark_id) > 0){
        foreach ($bookmark_id as $bid) {
          $bookmark = $this->storageBookmark->load($bid);
          break;
        }
      }

      if (!empty($bookmark)) {
        return new ResourceResponse($bookmark);
      }
      throw new NotFoundHttpException(t('Bookmark doesn\'t exist for @articleid article Id.', ['@articleid' => $article_id]));
    }

    throw new HttpException(t('Article id wasn\'t provided'));

  }

  /**
   * Responds to DELETE requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function delete($bookmark_nid = NULL) {

    if ($bookmark_nid) {
      $permission = 'delete bookmark entities';
      if(!$this->currentUser->hasPermission($permission)) {
        throw new AccessDeniedHttpException();
      }
      $bookmark = $this->storageBookmark->load($bookmark_nid);

      if (!empty($bookmark)) {
        return new ResourceResponse($bookmark->delete() == null ? "Bookmark deleted" : "Bookmarked wasn't delete.");
      }
      throw new NotFoundHttpException(t('Bookmark @bookmarknid were not found', array('@bookmarknid' => $bookmark_nid)));
    }

    throw new HttpException(t('Bookmarked wasn\'t provided'));
  }


  /**
   * Responds to POST requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($bookmark) {

    // Use current user after pass authentication to validate access.
    if (!$this->currentUser->hasPermission('add bookmark entities')) {
      throw new AccessDeniedHttpException();
    }
    $bookmark->save();

    return new ResourceResponse($bookmark);
  }

}
