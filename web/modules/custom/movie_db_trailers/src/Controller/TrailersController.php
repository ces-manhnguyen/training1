<?php

namespace Drupal\movie_db_trailers\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines TrailersController class.
 */
class TrailersController extends ControllerBase {

  /**
   * The pager manager object.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected $pagerManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('pager.manager')
    );
  }

  /**
   * Constructs a TrailersController object.
   *
   * @param \Drupal\Core\Pager\PagerManagerInterface $pager_manager
   *   A pager manager instance.
   */
  public function __construct(PagerManagerInterface $pager_manager) {
    $this->pagerManager = $pager_manager;
  }

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
      ];
    }, $entities);
    $trailer_rows = $this->returnPagerForArray($trailers, 10);

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
  function returnPagerForArray($items, $num_page) {
    // Get total items count
    $total = count($items);
    // Get the number of the current page
    $current_page = $this->pagerManager->createPager($total, $num_page)->getCurrentPage();
    // Split an array into chunks
    $chunks = array_chunk($items, $num_page);
    // Return current group item
    $current_page_items = $chunks[$current_page];

    return $current_page_items;
  }
}
