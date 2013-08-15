<!--**content_frame start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="col_sub">
                <div class="col_text">

                    <div class="title_h3">
                        <h3>模板文件列表</h3>
                    </div>
                    <hr class="hr_line"/>
                    <ul>
                        <?php
                        foreach ($theme_files as $item) {
                            echo '<li><a href="/site/theme?filename=' . $item . '">' . $item . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

            </div>
            <div class="clear"></div>
            <div class="main_wrap">
                <form method="post" action="/<?php echo url::current(TRUE); ?>">
                    <div class="clear">&nbsp;</div>
                    <h2><?php echo $filename; ?></h2>
                    <textarea cols="180" rows="30" name="file"><?php echo $data;?></textarea>
                    <div class="footcontent_frame">
                        <div class="btn_mainFoot">
                            <table style="margin: 0pt auto; width: auto;">
                                <tbody>
                                    <tr>
                                        <td>
                                                <input type="submit" class="ui-button" value="保存" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>
<!--**content_frame end**-->
