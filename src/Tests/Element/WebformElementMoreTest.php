<?php

namespace Drupal\webform\Tests\Element;

/**
 * Tests for element more.
 *
 * @group Webform
 */
class WebformElementMoreTest extends WebformElementTestBase {

  /**
   * Webforms to load.
   *
   * @var array
   */
  protected static $testWebforms = ['test_element_more'];

  /**
   * Test element more.
   */
  public function testMore() {
    $this->drupalGet('webform/test_element_more');

    // Check default more.
    $this->assertRaw('<div id="edit-more--more" class="js-webform-element-more webform-element-more">');
    $this->assertRaw('<div class="webform-element-more--link"><a role="button" aria-expanded="false" aria-controls="edit-more--more--content" href="#more">More</a></div>');

    // Check more with custom title.
    $this->assertRaw('<div id="edit-more-title--more" class="js-webform-element-more webform-element-more">');
    $this->assertRaw('<div class="webform-element-more--link"><a role="button" aria-expanded="false" aria-controls="edit-more-title--more--content" href="#more">{Custom more title}</a></div>');

    // Check more with HTML markup.
    $this->assertRaw('<div id="edit-more-html--more" class="js-webform-element-more webform-element-more">');
    $this->assertRaw('<div id="edit-more-html--more--content" class="webform-element-more--content">{This is an example of more with <b>HTML markup</b>}</div>');

    // Check more with description.
    $this->assertRaw('<div id="edit-more-title-description--description" class="webform-element-description">{This is an example of a description}</div>');
    $this->assertRaw('<div id="edit-more-title-description--more" class="js-webform-element-more webform-element-more">');

    // Check more with hidden description.
    $this->assertRaw('<div id="edit-more-title-description-hidden--description" class="webform-element-description visually-hidden">{This is an example of a hidden description}</div>');
    $this->assertRaw('<div id="edit-more-title-description-hidden--more" class="js-webform-element-more webform-element-more">');

    // Check datetime more.
    $this->assertRaw('<div id="edit-more-datetime--more" class="js-webform-element-more webform-element-more">');

    // Check fieldset more.
    $this->assertRaw('<div id="edit-more-fieldset--description" class="webform-element-description">{This is a description}</div>');
    $this->assertRaw('<div id="edit-more-fieldset--more" class="js-webform-element-more webform-element-more">');

    // Check details more.
    $this->assertRaw('<div id="edit-more-details--more" class="js-webform-element-more webform-element-more">');
    $this->assertRaw('<div class="webform-element-more--link"><a role="button" aria-expanded="false" aria-controls="edit-more-details--more--content" href="#more">More</a></div>');

    // Check tooltip ignored more.
    $this->assertRaw('<div id="edit-more-tooltip--description" class="webform-element-description visually-hidden">{This is a description}</div>');
    $this->assertRaw('<div id="edit-more-tooltip--more" class="js-webform-element-more webform-element-more">');
  }

}
