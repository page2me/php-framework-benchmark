<?php
/*
 * @filesource index/controllers/languageedit.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Languageedit;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Kotchasan\Language;
use \Kotchasan\DataTable;
use \Kotchasan\Form;

/**
 * ฟอร์มเขียน/แก้ไข ภาษา
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\Adminview
{

  /**
   * module=languageedit
   *
   * @return string
   */
  public function render(Request $request, $language)
  {
    // form แก้ไข
    $form = Html::create('form', array(
        'id' => 'setup_frm',
        'class' => 'setup_frm',
        'onsubmit' => 'doFormSubmit',
        'action' => 'index.php/index/model/languageedit/save',
        'ajax' => true
    ));
    // fieldset
    $fieldset = $form->add('fieldset', array(
      'title' => '{LNG_'.($language->id > 0 ? 'Edit' : 'Create').'} '.htmlspecialchars($language->key)
    ));
    // js
    $fieldset->add('select', array(
      'id' => 'write_js',
      'labelClass' => 'g-input icon-file',
      'label' => '{LNG_File}',
      'itemClass' => 'item',
      'options' => array(0 => 'php', 1 => 'js'),
      'value' => $language->js
    ));
    // type
    $fieldset->add('select', array(
      'id' => 'write_type',
      'labelClass' => 'g-input icon-config',
      'label' => '{LNG_Type}',
      'itemClass' => 'item',
      'options' => array('text' => 'Text', 'int' => 'Integer', 'array' => 'Array'),
      'value' => $language->type
    ));
    // owner
    $fieldset->add('select', array(
      'id' => 'write_owner',
      'labelClass' => 'g-input icon-modules',
      'label' => '{LNG_Module}',
      'itemClass' => 'item',
      'options' => \Index\Languageedit\Model::getOwners(),
      'value' => $language->owner
    ));
    // key
    $fieldset->add('text', array(
      'id' => 'write_key',
      'labelClass' => 'g-input icon-edit',
      'label' => '{LNG_Key}',
      'itemClass' => 'item',
      'autofocus',
      'value' => $language->key
    ));
    // table
    $table = new DataTable(array(
      'datas' => $language->datas,
      'onRow' => array($this, 'onRow'),
      'border' => true,
      'responsive' => true,
      'showCaption' => false,
      'pmButton' => true,
      'headers' => array(
        'key' => array(
          'text' => '{LNG_Key}'
        )
      )
    ));
    $div = $fieldset->add('div', array(
      'class' => 'item',
      'innerHTML' => $table->render()
    ));
    $div->add('div', array(
      'class' => 'comment',
      'innerHTML' => '{LNG_No need to enter text in English (en) or fill in the two matches}'
    ));
    // fieldset
    $fieldset = $form->add('fieldset', array(
      'class' => 'submit'
    ));
    // submit
    $fieldset->add('submit', array(
      'class' => 'button save large',
      'value' => '{LNG_Save}'
    ));
    // id
    $fieldset->add('hidden', array(
      'id' => 'write_id',
      'value' => $language->id
    ));
    return $form->render();
  }

  /**
   * จัดการแสดงผลแถวของตาราง
   *
   * @param array $item
   * @return array
   */
  public function onRow($item)
  {
    $item['key'] = Form::text(array(
        'name' => 'datas[key][]',
        'labelClass' => 'g-input',
        'value' => $item['key']
      ))->render();
    foreach (Language::installedLanguage() as $key) {
      $item[$key] = Form::textarea(array(
          'name' => 'datas['.$key.'][]',
          'labelClass' => 'g-input',
          'value' => isset($item[$key]) ? $item[$key] : '',
        ))->render();
    }
    return $item;
  }
}