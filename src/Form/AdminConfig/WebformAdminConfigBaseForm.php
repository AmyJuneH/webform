<?php

namespace Drupal\webform\Form\AdminConfig;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\Config;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\webform\Plugin\WebformElement\TableSelect;

/**
 * Base webform admin settings form.
 */
abstract class WebformAdminConfigBaseForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['webform.settings'];
  }

  /****************************************************************************/
  // Config helpers.
  /****************************************************************************/

  /**
   * Load webform.settings config.
   *
   * @return \Drupal\Core\Config\Config
   *   An editable configuration object.
   */
  protected function loadConfig() {
    $config = $this->config('webform.settings');
    _webform_config_load($config);
    return $config;
  }

  /**
   * Save webform.settings config.
   *
   * @param \Drupal\Core\Config\Config $config
   *   An editable configuration object.
   *
   * @return \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig
   */
  protected function saveConfig(Config  $config) {
    _webform_config_update($config);
    $config->save();
  }

  /****************************************************************************/
  // Exclude plugins.
  /****************************************************************************/

  /**
   * Build excluded plugins element.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   A webform element, handler, or exporter plugin manager.
   * @param array $excluded_ids
   *   An array of excluded ids.
   *
   * @return array
   *   A table select element used to excluded plugins by id.
   */
  protected function buildExcludedPlugins(PluginManagerInterface $plugin_manager, array $excluded_ids) {
    $plugins = $plugin_manager->getDefinitions();
    $plugins = $plugin_manager->getSortedDefinitions($plugins);

    $header = [
      'title' => ['data' => $this->t('Title')],
      'id' => ['data' => $this->t('Name'), 'class' => [RESPONSIVE_PRIORITY_LOW]],
      'description' => ['data' => $this->t('Description'), 'class' => [RESPONSIVE_PRIORITY_LOW]],
    ];

    $ids = [];
    $options = [];
    foreach ($plugins as $id => $plugin_definition) {
      $ids[$id] = $id;
      $options[$id] = [
        'title' => $plugin_definition['label'],
        'id' => $plugin_definition['id'],
        'description' => $plugin_definition['description'],
      ];
    }

    $element = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => array_diff($ids, $excluded_ids),
    ];
    TableSelect::setProcessTableSelectCallback($element);
    return $element;
  }

  /**
   * Convert included ids returned from table select element to excluded ids.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   A webform element, handler, or exporter plugin manager.
   * @param array $included_ids
   *   An array of included_ids.
   *
   * @return array
   *   An array of excluded ids.
   *
   * @see \Drupal\webform\Form\WebformAdminSettingsForm::buildExcludedPlugins
   */
  protected function convertIncludedToExcludedPluginIds(PluginManagerInterface $plugin_manager, array $included_ids) {
    $plugins = $plugin_manager->getDefinitions();
    $plugins = $plugin_manager->getSortedDefinitions($plugins);

    $ids = [];
    foreach ($plugins as $id => $plugin) {
      $ids[$id] = $id;
    }

    $excluded_ids = array_diff($ids, array_filter($included_ids));
    ksort($excluded_ids);
    return $excluded_ids;
  }

}
