<?php

namespace Drupal\specbee_assignment\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\specbee_assignment\services\TimeZoneService;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Provides a 'Custom' block.
 *
 * @Block(
 *   id = "specbee_assignment",
 *   admin_label = @Translation("Time Zone"),
 *   category = @Translation("Time Zone block")
 * )
 */
class TimeZoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The Time Zone.
   *
   * @var Drupal\specbee_assignment\services\TimeZoneService
   */
  protected $timezoneServive;

  /**
   * The Route Match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */

  protected $routeMatch;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a Timezone object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   * @param \Drupal\specbee_assignment\services\TimeZoneService $timezoneServive
   *   The Time Zone.
   * @param Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The Route Match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory, TimeZoneService $timezoneServive, RouteMatchInterface $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $configFactory;
    $this->timezoneServive = $timezoneServive;
    $this->routeMatch = $routeMatch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('config.factory'),
          $container->get('specbee_assignment.timezone'),
          $container->get('current_route_match')
      );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $all_timezone = timezone_identifiers_list();
    $timezone = $this->configFactory->get('specbee_assignment.default')->get('timezone');
    $country = $this->configFactory->get('specbee_assignment.default')->get('country');
    $city = $this->configFactory->get('specbee_assignment.default')->get('city');
    if (in_array($timezone, $all_timezone)) {
      $currentDateTime = $this->timezoneServive->getCurrentTimeZone($timezone);
      $error = ' ';
    }
    else {
      $error = 'No timezone is selected';
    }
    return [
      '#theme' => 'current_timezone',
      '#currentdatetime' => $currentDateTime,
      '#error' => $error,
      '#country' => $country,
      '#city' => $city,
      '#timezone' => $timezone,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
