<?php

/**
 * @file
 * Contains bookmark.page.inc.
 *
 * Page callback for Bookmark entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Bookmark templates.
 *
 * Default template: bookmark.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bookmark(array &$variables) {
  // Fetch Bookmark Entity Object.
  $bookmark = $variables['elements']['#bookmark'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
