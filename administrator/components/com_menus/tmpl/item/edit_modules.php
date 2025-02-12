<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Menus\Administrator\View\Item\HtmlView;

/** @var HtmlView $this */
foreach ($this->levels as $key => $value) {
    $allLevels[$value->id] = $value->title;
}

$this->getDocument()->addScriptOptions('menus-edit-modules', ['viewLevels' => $allLevels, 'itemId' => (int) $this->item->id]);

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('com_menus.admin-item-edit-modules')
    ->useScript('joomla.dialog-autocreate');

// Set up the modal options that will be used for module editor
$popupOptions = [
    'popupType'  => 'iframe',
    'textHeader' => Text::_('COM_MENUS_EDIT_MODULE_SETTINGS'),
    'className'  => 'menus-dialog-module-editing',
];

Text::script('JNO');
Text::script('JYES');
Text::script('JALL');
Text::script('JTRASHED');

?>
<?php
// Set main fields.
$this->fields = ['toggle_modules_assigned', 'toggle_modules_published'];

echo LayoutHelper::render('joomla.menu.edit_modules', $this); ?>

<table class="table" id="modules_assigned">
    <caption class="visually-hidden">
        <?php echo Text::_('COM_MENUS_MODULES_TABLE_CAPTION'); ?>
    </caption>
    <thead>
        <tr>
            <th scope="col" class="w-40">
                <?php echo Text::_('COM_MENUS_HEADING_ASSIGN_MODULE'); ?>
            </th>
            <th scope="col" class="w-15">
                <?php echo Text::_('COM_MENUS_HEADING_LEVELS'); ?>
            </th>
            <th scope="col" class="w-15">
                <?php echo Text::_('COM_MENUS_HEADING_POSITION'); ?>
            </th>
            <th scope="col">
                <?php echo Text::_('COM_MENUS_HEADING_DISPLAY'); ?>
            </th>
            <th scope="col">
                <?php echo Text::_('COM_MENUS_HEADING_PUBLISHED_ITEMS'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($this->modules as $i => $module) : ?>
        <?php if (is_null($module->menuid)) : ?>
            <?php if (!$module->except || $module->menuid < 0) : ?>
                <?php $no = 'no '; ?>
            <?php else : ?>
                <?php $no = ''; ?>
            <?php endif; ?>
        <?php else : ?>
            <?php $no = ''; ?>
        <?php endif; ?>
        <?php if ($module->published) : ?>
            <?php $status = ''; ?>
        <?php else : ?>
            <?php $status = 'unpublished '; ?>
        <?php endif; ?>
        <tr id="tr-<?php echo $module->id; ?>" class="<?php echo $no; ?><?php echo $status; ?>row<?php echo $i % 2; ?>">
            <th scope="row">
                <?php $popupOptions['src'] = Route::_('index.php?option=com_modules&task=module.edit&tmpl=component&layout=modal&id=' . $module->id, false); ?>
                <button type="button"
                    data-joomla-dialog="<?php echo $this->escape(json_encode($popupOptions, JSON_UNESCAPED_SLASHES)) ?>"
                    class="btn btn-link module-edit-link"
                    title="<?php echo Text::_('COM_MENUS_EDIT_MODULE_SETTINGS'); ?>"
                    id="title-<?php echo $module->id; ?>"
                    data-module-id="<?php echo $module->id; ?>"
                    data-checkin-url="<?php echo Route::_('index.php?option=com_modules&task=modules.checkin&format=json&cid[]=' . $module->id); ?>">
                    <?php echo $this->escape($module->title); ?></button>
            </th>
            <td id="access-<?php echo $module->id; ?>">
                <?php echo $this->escape($module->access_title); ?>
            </td>
            <td id="position-<?php echo $module->id; ?>">
                <?php echo $this->escape($module->position); ?>
            </td>
            <td id="menus-<?php echo $module->id; ?>">
                <?php if (is_null($module->menuid)) : ?>
                    <?php if ($module->except) : ?>
                        <span class="badge bg-success">
                            <?php echo Text::_('JYES'); ?>
                        </span>
                    <?php else : ?>
                        <span class="badge bg-danger">
                            <?php echo Text::_('JNO'); ?>
                        </span>
                    <?php endif; ?>
                <?php elseif ($module->menuid > 0) : ?>
                    <span class="badge bg-success">
                        <?php echo Text::_('JYES'); ?>
                    </span>
                <?php elseif ($module->menuid < 0) : ?>
                    <span class="badge bg-danger">
                        <?php echo Text::_('JNO'); ?>
                    </span>
                <?php else : ?>
                    <span class="badge bg-info">
                        <?php echo Text::_('JALL'); ?>
                    </span>
                <?php endif; ?>
            </td>
            <td id="status-<?php echo $module->id; ?>">
                <?php if ($module->published) : ?>
                    <span class="badge bg-success">
                        <?php echo Text::_('JYES'); ?>
                    </span>
                <?php else : ?>
                    <span class="badge bg-danger">
                        <?php echo Text::_('JNO'); ?>
                    </span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
