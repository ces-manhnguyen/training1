<?php

namespace Drupal\cinemas\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\cinemas\Service\CinemasService;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'Cinemas' Block.
 *
 * @Block(
 *   id = "cinemas_block",
 *   admin_label = @Translation("Cinemas block"),
 *   category = @Translation("Cinemas Block"),
 * )
 */
class CinemasBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The cinemas service object.
   *
   * @var \Drupal\cinemas\Service\CinemasService
   */
  protected $cinemasService;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $currentRouteMatch;

  /**
   * {@inheritdoc}
   * 
   * @param Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container.
   * @param array $configuration
   *   Configuration.
   * @param string $plugin_id
   *   Plugin Id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('cinemas_service'),
      $container->get('current_route_match'),
    );
  }

  /**
   * Constructs a CinemasController object.
   *
   * @param array $configuration
   *   Configuration.
   * @param string $plugin_id
   *   Plugin Id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * 
   * @param \Drupal\cinemas\Service\CinemasService $cinemas_service
   *   A cinemas service instance.
   * 
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *  A current route match instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CinemasService $cinemas_service, RouteMatchInterface $current_route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->cinemasService = $cinemas_service;
    $this->currentRouteMatch = $current_route_match;
  }

  /**
   * Display the page.
   *
   * @return array
   *   Return a renderable array.
   */
  public function build() {
    $node = $this->currentRouteMatch->getParameter('node');
    $cinemas = $node == null ? [] : $this->cinemasService->fetchCinemas($node->id());

    return [
      '#title' => 'Cinemas',
      'table' => [
        '#theme' => 'cinemas_page',
        '#cinemas' => $cinemas,
      ],
      '#attached' => ['library' => ['core/drupal.dialog.ajax']],
    ];
  }
}
