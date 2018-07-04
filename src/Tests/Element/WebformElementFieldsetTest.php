<?php

namespace Drupal\webform\Tests\Element;

/**
 * Tests for fieldset element.
 *
 * @group Webform
 */
class WebformElementFieldsetTest extends WebformElementTestBase {

  /**
   * Webforms to load.
   *
   * @var array
   */
  protected static $testWebforms = ['test_element_fieldset'];

  /**
   * Test fieldset element.
   */
  public function testFieldset() {
    $this->drupalGet('webform/test_element_fieldset');

    // Check fieldset with help, field prefix, field suffix, description,
    // and more. Also, check that invalid 'required' and 'aria-required'
    // attributes are removed.
    $this->assertRaw('<fieldset class="webform-has-field-prefix webform-has-field-suffix required js-form-item form-item js-form-wrapper form-wrapper" data-drupal-selector="edit-fieldset" aria-describedby="edit-fieldset--description" id="edit-fieldset">');
    $this->assertRaw('<span class="fieldset-legend js-form-required form-required">Fieldset<a href="#help" title="This is help text." class="webform-element-help" data-webform-help="This is help text.">?</a>');
    $this->assertRaw('<span class="field-prefix">prefix</span>');
    $this->assertRaw('<span class="field-suffix">suffix</span>');
    $this->assertRaw('<div class="description">');
    $this->assertRaw('<div id="edit-fieldset--description" class="webform-element-description">This is a description.</div>');
    $this->assertRaw('<div id="edit-fieldset--more" class="js-webform-element-more webform-element-more">');

    // Check fieldset title_display: invisible.
    $this->assertRaw('<span class="visually-hidden fieldset-legend">Fieldset title invisible</span>');
  }

}
