<?php

namespace Drupal\webform\Plugin\WebformElement;

/**
 * Provides a 'webform_entity_select' element.
 *
 * @WebformElement(
 *   id = "webform_entity_select",
 *   label = @Translation("Entity select"),
 *   description = @Translation("Provides a form element to select a single or multiple entity references using a select menu."),
 *   category = @Translation("Entity reference elements"),
 *   multiple = TRUE,
 * )
 */
class WebformEntitySelect extends Select implements WebformEntityReferenceInterface {

  use WebformEntityReferenceTrait;
  use WebformEntityOptionsTrait;

}
