<?php

namespace Drupal\Tests\entity_print\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\entity_print\Plugin\EntityPrintPluginManager
 * @group entity_print
 */
class EntityPrintPluginManagerTest extends KernelTestBase {

  public static $modules = ['entity_print', 'entity_print_test'];

  /**
   * The plugin manager.
   *
   * @var \Drupal\entity_print\Plugin\EntityPrintPluginManagerInterface
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->pluginManager = $this->container->get('plugin.manager.entity_print.print_engine');
  }

  /**
   * Ensure that an empty plugin ID does not break the in unusual ways.
   *
   * @covers ::createSelectedInstance
   * @expectedException \Drupal\entity_print\PrintEngineException
   */
  public function testCreateSelectedInstance() {
    /** @var \Drupal\Core\Config\ConfigFactoryInterface $factory */
    $factory = $this->container->get('config.factory');
    $config = $factory->getEditable('entity_print.settings');
    $config->set('print_engines', ['pdf_engine' => '']);
    $config->save();

    $this->pluginManager->createSelectedInstance('pdf');
  }

  /**
   * Test if an engine is enabled.
   *
   * @covers ::isPrintEngineEnabled
   * @dataProvider isPrintEngineEnabledDataProvider
   */
  public function testIsPrintEngineEnabled($plugin_id, $is_enabled) {
    $this->assertSame($this->pluginManager->isPrintEngineEnabled($plugin_id), $is_enabled);
  }

  /**
   * Data provider for isPrintEngineEnabled test.
   */
  public function isPrintEngineEnabledDataProvider() {
    return [
      'Non-existent plugin ID' => ['abc123', FALSE],
      'Empty plugin ID' => ['', FALSE],
      'Disabled plugin ID' => ['dompdf', FALSE],
      'Enabled plugin ID' => ['testprintengine', TRUE],
    ];
  }

  /**
   * @covers ::getDisabledDefinitions
   * @dataProvider getDisabledDefinitionsDataProvider
   */
  public function testGetDisabledDefinitions($filter, $expected_definitions) {
    $disabled_definitions = array_keys($this->pluginManager->getDisabledDefinitions($filter));
    sort($disabled_definitions);
    sort($expected_definitions);
    $this->assertSame($disabled_definitions, $expected_definitions);
  }

  /**
   * Data provider for getDisabledDefinitions test.
   */
  public function getDisabledDefinitionsDataProvider() {
    return [
      'Filter by pdf' => ['pdf', ['dompdf', 'phpwkhtmltopdf', 'not_available_print_engine']],
      'Filter by another type' => ['word_docx', []],
    ];
  }

}
