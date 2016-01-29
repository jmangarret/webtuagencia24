<?php
defined('_JEXEC') or die('Restricted access');
$option = JRequest::getCmd('option');
?>
<script type="text/javascript">
<!--
jQuery.noConflict()(document).ready(function ($) {
	$("a.comment_links").click(function (event) {
		event.preventDefault();
		var cell = $(this).closest("td");
        console.log(cell.children(".end_comment"));
		cell.children(".end_comment").toggle();
		cell.children("a.comment_links").toggle();
	});
});
//-->
</script>

<form action="index.php" method="post" name="adminForm">
<table width="100%">
<tr>
	<td align="right">
		<?php
			echo JText::_('CP.SELECT_A_PRODUCT_TYPE') . '&nbsp;' . $this->lists['productTypes'] . '&nbsp;' . $this->lists['state'];
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
                    <?php echo JHTML::_('grid.sort', 'Name', 'created_by', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                    <?php echo JHTML::_('grid.sort', 'CP.COMMENTS_LIST_EMAIL_LABEL', 'contact_email', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                    <?php echo JHTML::_('grid.sort', 'CP.COMMENTS_LIST_CREATED_DATE_LABEL', 'created', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                    <?php echo JHTML::_('grid.sort', 'CP.PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.COMMENTS_LIST_END_DATE_LABEL', 'end_date', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JHTML::_('grid.sort', 'CP.COMMENTS_RATE_LABEL', 'comment_rate', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
                <?php echo JText::_('CP.COMMENT'); ?>
            </th>
			<th nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'STATE', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="1%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort', 'CP.ID', 'comment_id', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
            <tr>
                    <td colspan="11">
                            <?php echo $this->pagination->getListFooter(); ?>
                    </td>
            </tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	$imgY = 'tick.png';
	$imgX = 'publish_x.png';
	$pending_text = JText::_('CP.COMMENTS_NEW_STATE');
	$published_text = JText::_('PUBLISHED');
	$unpublished_text = JText::_('UNPUBLISHED');
	$published_action = JText::_('UNPUBLISH ITEM');
	$unpublished_action = JText::_('PUBLISH ITEM');
	// Se imprime la información de cada registro
    for ($i = 0, $n = count($this->data); $i < $n; $i++) {
        $row = &$this->data[$i];
		$checked = JHTML::_('grid.id', $i, $row->comment_id);
		$end_date = JHTML::_('date', $row->end_date, 'd/m/Y H:i');
		switch ($row->published) {
			case 0: // despublicado
				$alt = $unpublished_text;
				$img = 'publish_x.png';
				$task = 'publish';
				$action = $unpublished_action;
				break;
			case 1: // publicado
				$alt = $published_text;
				$img = 'tick.png';
				$task = 'unpublish';
				$action = $published_action;
				break;
			case 2: // pendiente de revisión
				$alt = $pending_text;
				$img = 'disabled.png';
				$task = 'publish';
				$action = $unpublished_action;
				break;
		}
        $published = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
        <img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>';
		$created_date = JHTML::_('date', $row->created, 'd/m/Y H:i');
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php echo $row->contact_name; ?>
			</td>
			<td>
				<?php echo $row->contact_email; ?>
			</td>
			<td align="center">
				<?php echo $created_date; ?>
			</td>
            <td>
                <?php echo $row->product_name; ?>
            </td>
            <td align="center">
                <?php echo $end_date; ?>
            </td>
            <td align="center">
                <?php echo $row->comment_rate; ?>
            </td>
            <td align="justify" class="comment_text">
                <?php if (strlen($row->comment_text) > 50): ?>
                <span class="start_comment"><?php echo nl2br(substr($row->comment_text, 0, 50)); ?></span><span class="end_comment"><?php echo nl2br(substr($row->comment_text, 50)); ?></span>
                <a href="#" class="comment_links"><?php echo JText::_('CP.COMMENTS_SHOW_MORE_LINK'); ?></a>
                <a href="#" class="comment_links show_less_link"><?php echo JText::_('CP.COMMENTS_SHOW_LESS_LINK'); ?></a>
                <?php else: ?>
                <?php echo $row->comment_text; ?>
                <?php endif; ?>
            </td>
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td align="center">
				<?php echo $row->comment_id; ?>
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