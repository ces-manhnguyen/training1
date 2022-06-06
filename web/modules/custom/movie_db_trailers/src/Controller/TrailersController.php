<?php

namespace Drupal\movie_db_trailers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

/**
 * Defines TrailersController class.
 */
class TrailersController extends ControllerBase {

  /**
   * Display the page.
   *
   * @return array
   *   Return a renderable array.
   */
  public function content(NodeInterface $node) {
    $title = 'Trailers of ' . $node->getTitle();
    $entities = $node->field_trailers->referencedEntities();
    $trailers = array_map(function ($entity) {
      return [
        'title' => $entity->name->value,
        'link' => '/trailer/' . $entity->mid->value,
        // 'link' => $entity->field_media_oembed_video_1->value,
      ];
    }, $entities);
    $trailer_rows = _return_pager_for_array($trailers, 10);
    return [
      '#title' => $title,
      'table' => [
        '#theme' => 'trailers_page',
        '#trailers' => $trailer_rows,
      ],
      'pager' => [
        '#type' => 'pager',
      ],
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }
}

/**
 * Split array for pager.
 *
 * @param array $items
 *   Items which need split
 *
 * @param integer $num_page
 *   How many items view in page
 *
 * @return array
 */
function _return_pager_for_array($items, $num_page) {
  // Get total items count
  $total = count($items);
  // Get the number of the current page
  $current_page = \Drupal::service('pager.manager')->createPager($total, $num_page)->getCurrentPage();
  // Split an array into chunks
  $chunks = array_chunk($items, $num_page);
  // Return current group item
  $current_page_items = $chunks[$current_page];

  return $current_page_items;
}
