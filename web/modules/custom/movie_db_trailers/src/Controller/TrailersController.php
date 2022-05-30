<?php

namespace Drupal\movie_db_trailers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

/**
 * Defines HelloController class.
 */
class TrailersController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content(NodeInterface $node) {
    // dump($node->field_trailers);
    $is_movie= $node->getType()=='movie';
    $title= $node->getTitle();

    return [
      '#type' => 'markup',
      '#markup' => $this->t('@title',['@title'=>$is_movie?$title:'Page not found']),
    ];
  }

}
