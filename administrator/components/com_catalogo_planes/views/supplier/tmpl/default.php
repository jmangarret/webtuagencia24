<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
?>

<form action="index.php" method="post" name="adminForm">
<table>
<tr>
    <td align="left" width="100%">
        <?php echo JText::_('FILTER'); ?>:
        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->lists['search']); ?>" class="text_area" onchange="document.adminForm.submit();" />
        <button onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
        <button onclick="document.getElementById('search').value='';document.getElementById('filter_country_id').selectedIndex=0;document.getElementById('filter_city_id').selectedIndex=0;this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('RESET'); ?></button>
    </td>
    <td nowrap="nowrap">
        <?php
            echo $this->lists['countries'] . '&nbsp;' . $this->lists['cities'] . '&nbsp;' . $this->lists['state'];
        ?>
    </td>
</tr>
</table>
<div id="editcell">
    <table class="adminlist">
    <thead>
        <tr>
            <th width="5">
                <?php echo JText::_('NUM'); ?>
            </th>
            <th width="20">
                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->data); ?>);" />
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'Name', 'supplier_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.SUPPLIER_CODE', 'supplier_code', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.SUPPLIER_URL', 'url', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.SUPPLIER_EMAIL', 'email', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.COUNTRY', 'country_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.CITY', 'city_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'STATE', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ID', 'supplier_id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="10">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
    <?php
    $k = 0;
    // Se imprime la información de cada registro
    $url = 'index.php?option=' . $option . '&view=' . $this->viewName . '&task=edit&cid[]=';
    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row = &$this->data[$i];

        $link   = JRoute::_($url . $row->supplier_id);

        $checked = JHTML::_('grid.id', $i, $row->supplier_id);

        $published  = JHTML::_('grid.published', $row, $i);

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $this->pagination->getRowOffset($i); ?>
            </td>
            <td>
                <?php echo $checked; ?>
            </td>
            <td>
                <span class="editlinktip hasTip" title="<?php echo JText::_('EDIT'); ?>::<?php echo $this->escape($row->supplier_name); ?>">
                    <a href="<?php echo $link; ?>">
                        <?php echo $this->escape($row->supplier_name); ?></a></span>
            </td>
            <td align="center">
                <?php echo $row->supplier_code; ?>
            </td>
            <td align="center">
                <?php echo $row->url; ?>
            </td>
            <td align="center">
                <?php echo $row->email; ?>
            </td>
            <td align="center">
                <?php echo $row->country_name; ?>
            </td>
            <td align="center">
                <?php echo $row->city_name; ?>
            </td>
            <td align="center">
                <?php echo $published; ?>
            </td>
            <td align="center">
                <?php echo $row->supplier_id; ?>
            </td>
        </tr>
        <?php
        $k = 1 - $k;
    }
    ?>
    </tbody>
    </table>
</div>

    <input type="hidden" name="view" value="<?php echo $this->viewName; ?>" />
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>