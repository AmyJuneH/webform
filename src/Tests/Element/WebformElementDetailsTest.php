<?php

namespace Drupal\webform\Tests\Element;

/**
 * Tests for details element.
 *
 * @group Webform
 */
class WebformElementDetailsTest extends WebformElementTestBase {

  /**
   * Webforms to load.
   *
   * @var array
   */
  protected static $testWebforms = ['test_element_details'];

  /**
   * Test details element.
   */
  public function testDetails() {
    $this->drupalGet('webform/test_element_details');

    // Check details with help, field prefix, field suffix, description,
    // and more. Also, check that invalid 'required' and 'aria-required'
    // attributes are removed.
    $this->assertRaw('<details data-webform-key="details" data-drupal-selector="edit-details" aria-describedby="edit-details--description" id="edit-details" class="js-form-wrapper form-wrapper required" open="open">    <summary role="button" aria-controls="edit-details" aria-expanded="true" aria-pressed="true" class="js-form-required form-required">details<a href="#help" title="This is help text." class="webform-element-help" data-webform-help="This is help text.">?</a>');
    $this->assertRaw('<div class="details-description"><div id="edit-details--description" class="webform-element-description">This is a description.</div>');
    $this->assertRaw('<div id="edit-details--more" class="js-webform-element-more webform-element-more">');

    // Check details title_display: invisible.
    $this->assertRaw('<summary role="button" aria-controls="edit-details-title-invisible" aria-expanded="false" aria-pressed="false"><span class="visually-hidden">Details title invisible</span></summary>');
  }

}
