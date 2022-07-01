<?php

namespace Drupal\cinemas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\cinemas\Service\CinemasService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines CinemasController class.
 */
class CinemasController extends ControllerBase {

  /**
   * The cinemas service object.
   *
   * @var \Drupal\cinemas\Service\CinemasService
   */
  protected $cinemasService;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cinemas_service')
    );
  }

  /**
   * Constructs a CinemasController object.
   *
   * @param \Drupal\cinemas\Service\CinemasService $cinemas_service
   *   A cinemas service instance.
   */
  public function __construct(CinemasService $cinemas_service) {
    $this->cinemasService = $cinemas_service;
  }

  /**
   * Display the page.
   *
   * @return array
   *   Return a renderable array.
   */
  public function content(NodeInterface $node) {
    $title = 'Cinemas of ' . $node->getTitle();
    $cinemas = $this->cinemasService->fetchCinemas($node->id());

    return [
      '#title' => $title,
      'table' => [
        '#theme' => 'cinemas_page',
        '#cinemas' => $cinemas,
      ],
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }
}
