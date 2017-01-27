<?php

namespace Drupal\webform\Tests\Element;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\user\Entity\User;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformSubmission;
use Drupal\webform\Tests\WebformTestBase;
use Drupal\webform\WebformSubmissionInterface;

/**
 * Tests for webform submission webform element custom #format support.
 *
 * @group Webform
 */
class WebformElementFormatTest extends WebformTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['node', 'webform'];


  /**
   * Webforms to load.
   *
   * @var array
   */
  protected static $testWebforms = ['test_element_format', 'test_element_format_multiple', 'test_element_format_token'];

  /**
   * Tests element format.
   */
  public function testElementFormat() {

    /**************************************************************************/
    /* Format element (single) item as HTML and text */
    /**************************************************************************/

    /** @var \Drupal\webform\WebformInterface $webform_format_item */
    $webform_format_item = Webform::load('test_element_format');
    $sid = $this->postSubmission($webform_format_item, [], t('Submit'));
    $webform_format_item_submission = WebformSubmission::load($sid);

    // Check elements (single) item formatted as HTML.
    $body = $this->getMessageBody($webform_format_item_submission, 'email_html');
    $elements = [
      'Checkbox (Value)' => 'Yes',
      'Color (Color swatch)' => '<span style="display:inline-block; height:1em; width:1em; border:1px solid #000; background-color:#ffffcc"></span> #ffffcc',
      'Email (Link)' => '<a href="mailto:example@example.com">example@example.com</a>',
      'Email confirm (Link)' => '<a href="mailto:example@example.com">example@example.com</a>',
      'Email multiple (Link)' => '<a href="mailto:example@example.com">example@example.com</a>, <a href="mailto:test@test.com">test@test.com</a>, <a href="mailto:random@random.com">random@random.com</a>',
      'Signature (Status)' => '[signed]',
      'Signature (Image)' => '[signed]',
      'Telephone (Link)' => '<a href="tel:123-456-7890">123-456-7890</a>',
      'Text format (Plain text)' => '<p>&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Negat esse eam, inquit, propter se expetendam. Primum Theophrasti, Strato, physicum se voluit; Id mihi magnum videtur. Itaque mihi non satis videmini considerare quod iter sit naturae quaeque progressio. Quare hoc videndum est, possitne nobis hoc ratio philosophorum dare. Est enim tanti philosophi tamque nobilis audacter sua decreta defendere.&lt;/p&gt;</p>',
      'Toggle (Value)' => 'No',
      'URL (Link)' => '<a href="http://example.com">http://example.com</a>',
      'Likert (Value)' => '<div class="item-list"><ul><li><b>Please answer question 1?:</b> 1</li><li><b>How about now answering question 2?:</b> 1</li><li><b>Finally, here is question 3?:</b> 1</li></ul></div>',
      'Likert (Raw value)' => '<div class="item-list"><ul><li><b>q1:</b> 1</li><li><b>q2:</b> 1</li><li><b>q3:</b> 1</li></ul></div>',
      'Likert (List)' => '<div class="item-list"><ul><li><b>Please answer question 1?:</b> 1</li><li><b>How about now answering question 2?:</b> 1</li><li><b>Finally, here is question 3?:</b> 1</li></ul></div>',
      'Date (Raw value)' => 'Thu, 18 Jun 1942 00:00:00 +1000am4',
      'Date (Fallback date format)' => 'Thu, 06/18/1942 - 00:00',
      'Date (HTML Date)' => '1942-06-18',
      'Date (HTML Datetime)' => '1942-06-18T00:00:00+1000',
      'Date (HTML Month)' => '1942-06',
      'Date (HTML Time)' => '00:00:00',
      'Date (HTML Week)' => '1942-W25',
      'Date (HTML Year)' => '1942',
      'Date (HTML Yearless date)' => '06-18',
      'Date (Default long date)' => 'Thursday, June 18, 1942 - 00:00',
      'Date (Default medium date)' => 'Thu, 06/18/1942 - 00:00',
      'Date (Default short date)' => '06/18/1942 - 00:00',
      'Time (Value)' => '09:00',
      'Time (Raw value)' => '09:00:00',
      'Entity autocomplete (Raw value)' => 'user:1',
      // 'Entity autocomplete (Link)' => '<a href="http://localhost/webform/user/1" hreflang="en">admin</a>',
//      'Entity autocomplete (Entity ID)' => '1',
//      'Entity autocomplete (Label)' => 'admin',
//      'Entity autocomplete (Label (ID))' => 'admin (1)',
      'Address (Value)' => '10 Main Street<br />10 Main Street<br />Springfield, Alabama. Loremipsum<br />Afghanistan<br /><br/><br/>',
      'Address (Raw value)' => '<div class="item-list"><ul><li><b>address:</b> 10 Main Street</li><li><b>address_2:</b> 10 Main Street</li><li><b>city:</b> Springfield</li><li><b>state_province:</b> Alabama</li><li><b>postal_code:</b> Loremipsum</li><li><b>country:</b> Afghanistan</li></ul></div><br/><br/>',
      'Address (List)' => '<div class="item-list"><ul><li><b>Address:</b> 10 Main Street</li><li><b>Address 2:</b> 10 Main Street</li><li><b>City/Town:</b> Springfield</li><li><b>State/Province:</b> Alabama</li><li><b>Zip/Postal Code:</b> Loremipsum</li><li><b>Country:</b> Afghanistan</li></ul></div><br/><br/>',
    ];
    foreach ($elements as $label => $value) {
      $this->assertContains($body, '<b>' . $label . '</b><br/>' . $value, new FormattableMarkup('Found @label: @value', ['@label' => $label, '@value' => $value]));
    }

    // Check code format.
    $this->assertContains($body, '<pre class="js-webform-codemirror-runmode webform-codemirror-runmode" data-webform-codemirror-mode="text/x-yaml">message: \'Hello World\'</pre>');

    // Check elements formatted as text.
    $body = $this->getMessageBody($webform_format_item_submission, 'email_text');
    $elements = [
      'Checkbox (Value): Yes',
      'Color (Color swatch): #ffffcc',
      'Email (Link): example@example.com',
      'Email multiple (Link): example@example.com, test@test.com, random@random.com',
      'Toggle (Value): No',
      'URL (Link): http://example.com',
      'Address (Value):
10 Main Street
10 Main Street
Springfield, Alabama. Loremipsum
Afghanistan',
      'Address (Raw value):
address: 10 Main Street
address_2: 10 Main Street
city: Springfield
state_province: Alabama
postal_code: Loremipsum
country: Afghanistan',
      'Address (List):
Address: 10 Main Street
Address 2: 10 Main Street
City/Town: Springfield
State/Province: Alabama
Zip/Postal Code: Loremipsum
Country: Afghanistan',
    'Likert (Value):
Please answer question 1?: 1
How about now answering question 2?: 1
Finally, here is question 3?: 1',
    'Likert (Raw value):
q1: 1
q2: 1
q3: 1',
    'Likert (List):
Please answer question 1?: 1
How about now answering question 2?: 1
Finally, here is question 3?: 1',
    'Likert (Table):
Please answer question 1?: 1
How about now answering question 2?: 1
Finally, here is question 3?: 1',
    'Date (Value): vamThursday000000Australia/Sydney',
    'Date (Raw value): Thu, 18 Jun 1942 00:00:00 +1000am4',
    'Date (Fallback date format): Thu, 06/18/1942 - 00:00',
    'Date (HTML Date): 1942-06-18',
    'Date (HTML Datetime): 1942-06-18T00:00:00+1000',
    'Date (HTML Month): 1942-06',
    'Date (HTML Time): 00:00:00',
    'Date (HTML Week): 1942-W25',
    'Date (HTML Year): 1942',
    'Date (HTML Yearless date): 06-18',
    'Date (Default long date): Thursday, June 18, 1942 - 00:00',
    'Date (Default medium date): Thu, 06/18/1942 - 00:00',
    'Date (Default short date): 06/18/1942 - 00:00',
    'Time (Value): 09:00',
    'Time (Raw value): 09:00:00',
    ];
    foreach ($elements as $value) {
      $this->assertContains($body, $value, new FormattableMarkup('Found @value', ['@value' => $value]));
    }

    /**************************************************************************/
    /* Format element (multiple) items as HTML and text */
    /**************************************************************************/

    /** @var \Drupal\webform\WebformInterface $webform_format_items */
    $webform_format_items = Webform::load('test_element_format_multiple');
    $sid = $this->postSubmission($webform_format_items, [], t('Submit'));
    $webform_format_items_submission = WebformSubmission::load($sid);

    // Check elements (single) item formatted as HTML.
    $body = $this->getMessageBody($webform_format_items_submission, 'email_html');
    $elements = [
      'Checkboxes (Comma)' => 'One, Two, Three',
      'Checkboxes (Semicolon)' => 'One; Two; Three',
      'Checkboxes (And)' => 'One, Two, and Three',
      'Checkboxes (Ordered list)' => '<div class="item-list"><ol><li>One</li><li>Two</li><li>Three</li></ol>',
      'Checkboxes (Unordered list)' => '<div class="item-list"><ul><li>One</li><li>Two</li><li>Three</li></ul>',
    ];
    foreach ($elements as $label => $value) {
      $this->assertContains($body, '<b>' . $label . '</b><br/>' . $value, new FormattableMarkup('Found @label: @value', ['@label' => $label, '@value' => $value]));
    }

    // Check elements formatted as text.
    $body = $this->getMessageBody($webform_format_items_submission, 'email_text');
    $elements = [
      'Checkboxes (Comma): One, Two, Three',
      'Checkboxes (Semicolon): One; Two; Three',
      'Checkboxes (And): One, Two, and Three',
      'Checkboxes (Ordered list):
1. One
2. Two
3. Three',
      'Checkboxes (Unordered list):
- One
- Two
- Three',
    ];
    foreach ($elements as $value) {
      $this->assertContains($body, $value, new FormattableMarkup('Found @value', ['@value' => $value]));
    }

    /**************************************************************************/
    /* Format element using tokens */
    /**************************************************************************/

    /** @var \Drupal\webform\WebformInterface $webform_format_token */
    $webform_format_token = Webform::load('test_element_format_token');
    $sid = $this->postSubmission($webform_format_token);
    $webform_format_token_submission = WebformSubmission::load($sid);

    // Check elements tokens formatted as HTML.
    $body = $this->getMessageBody($webform_format_token_submission, 'email_html');
    $elements = [
      'default:' => 'one, two, three',
      'comma:' => 'one, two, three',
      'semicolon:' => 'one; two; three',
      'and:' => 'one, two, and three',
      'ul:' => '<div class="item-list"><ul><li>one</li><li>two</li><li>three</li></ul></div>',
      'ol:' => '<div class="item-list"><ol><li>one</li><li>two</li><li>three</li></ol></div>',
      'raw:' => '1, 2, 3',
    ];
    foreach ($elements as $label => $value) {
      $this->assertContains($body, '<h3>' . $label . '</h3>' . $value . '<hr/>', new FormattableMarkup('Found @label: @value', ['@label' => $label, '@value' => $value]));
    }

    // Check elements tokens formatted as text.
    $body = $this->getMessageBody($webform_format_token_submission, 'email_text');
    $elements = [
      "default:\none, two, three",
      "comma:\none, two, three",
      "semicolon:\none; two; three",
      "and:\none, two, and three",
      "ul:\n- one\n- two\n- three",
      "ol:\n1. one\n2. two\n3. three",
      "raw:\n1, 2, 3",
    ];
    foreach ($elements as $value) {
      $this->assertContains($body, $value, new FormattableMarkup('Found @value', ['@value' => $value]));
    }

    // Check element default format item global setting.
    \Drupal::configFactory()->getEditable('webform.settings')
      ->set('format.checkboxes.item', 'raw')
      ->save();
    $body = $this->getMessageBody($webform_format_token_submission, 'email_text');
    $this->assertContains($body, "default:\n1, 2, 3");

    // Check element default format items global setting.
    \Drupal::configFactory()->getEditable('webform.settings')
      ->set('format.checkboxes.items', 'and')
      ->save();
    $body = $this->getMessageBody($webform_format_token_submission, 'email_text');
    $this->assertContains($body, "default:\n1, 2, and 3");
  }

  /**
   * Get webform email message body for a webform submission.
   *
   * @param \Drupal\webform\WebformSubmissionInterface $submission
   *   A webform submission.
   * @param string $handler_id
   *   The webform email handler id.
   *
   * @return string
   *   The webform email message body for a webform submission.
   */
  protected function getMessageBody(WebformSubmissionInterface $submission, $handler_id = 'email_html') {
    /** @var \Drupal\webform\WebformHandlerMessageInterface $message_handler */
    $message_handler = $submission->getWebform()->getHandler($handler_id);
    $message = $message_handler->getMessage($submission);
    $body = (string) $message['body'];
    $this->verbose($body);
    return $body;
  }

}