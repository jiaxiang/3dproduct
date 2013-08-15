<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<div class="dialog" id="notice_dialog" title="公告">
    <div class="newgrid">
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <?php echo view_tool::sort('IP/地址', 12, 100);?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo long2ip($rs['ip']);?>&nbsp;</td>
                    </tr>
                </tbody>
        </table>
    </div>
</div>