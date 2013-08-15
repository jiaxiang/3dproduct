<style type="text/css">
 <!--
body {font:12px/1.5 Tahoma,Helvetica,Arial,'宋体',sans-serif; }
html {color:#404040; background: #fff; }
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,p,blockquote,th,td {margin:0;padding: 0;}
fieldset,img {border: none;}
table {border-collapse: inherit;border-spacing:1px;}
address,caption,cite,code,dfn,em,strong,th,var {font-style: normal;font-weight: normal;}
li { list-style: none; }
caption,th { text-align: left; }
h1,h2,h3,h4,h5,h6 { font-size: 100%; }
h1 { font-size: 18px; /* 18px / 12px = 1.5 */ }
h2 { font-size: 16px; }
h3 { font-size: 14px; }

.clear{clear:both;}
  a{color: #36c;text-decoration: none; }
  A:visited {color:#36c; text-decoration: none }
  A:link {	color:#36c; text-decoration: none }
  A:active {color:#36c; text-decoration: underline }
  A:hover {	color:#f60;text-decoration: underline;}
  OL {	color: #333333;font-family: tahoma,helvetica,sans-serif }
  UL {	color: #333333;font-family: tahoma,helvetica,sans-serif }
  P {	color: #333333; font-family: tahoma,helvetica,sans-serif }


  td {color: #333333; font-family: tahoma,helvetica,sans-serif;vertical-align:middle;font-weight:normal;}
  tr {color: #333333; font-family: tahoma,helvetica,sans-serif;vertical-align:middle;font-weight:normal;}
  th {color: #333333; font-family: tahoma,helvetica,sans-serif;vertical-align:middle;font-weight:normal;}
  td, th {padding:0;}
  font.title {	background-color: white; color: #363636; font-family:tahoma; font-size: 10pt; font-weight: bold }
  font.sub {	background-color: white; color: #000000; font-family:tahoma; font-size: 10pt; }
  font.layer {	color: #ff0000; font-family: courrier,sans-serif,arial,helvetica; font-size: 8pt; text-align: left }
  td.title {background-color:#cfd6e8; color:#002c7e; font-family:Tahoma; font-size: 10pt; font-weight: bold; height:25px; text-align: left;padding:0 0 0 10px; }
  td.sub {	background-color:#ededed; color: #555555; font-family:tahoma; font-size: 10pt; font-weight: bold; height:20px;text-align: left;padding:0 0 0 10px; }
  td.content {	background-color: white; color: #000000; font-family:tahoma; font-size: 8pt; text-align: left; height:20px; vertical-align: middle;padding:0 0 0 10px; }
  td.default {	background-color: white; color: #000000;font-family:tahoma; font-size: 8pt;padding:0 0 0 10px; height:20px; text-align:left; }
  td.border {	background-color: #cccccc; color: black; font-family: tahoma; font-size: 10pt; height: 25px }
  td.border-HILIGHT {	background-color: #ffffcc; color: black; font-family:verdana; font-size: 10pt; height: 25px }
-->
</style>
<script type="text/javascript">
    $(document).ready(function(){
        //define config object
        var dialogOpts = {
            title: "Network Security",
            modal: true,
            autoOpen: false,
            height: 500,
            width: 600
        };
        $("#example").dialog(dialogOpts);

        $(".contentsmall").each(function(){
            var id = $(this).html();
            $(this).click(function (){
                $("#example").html("loading...");
                $.ajax({
            		url: '<?php echo url::base();?>site/scan/ajax_content' + '?id=' + id,
                    type: 'GET',
                    dataType: 'json',
                    error: function() {
                        window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                    },
                    success: function(retdat, status) {
        				ajax_block.close();
        				if (retdat['code'] == 200 && retdat['status'] == 1) {
        					$("#example").html(retdat['content']);
        				} else {
        					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
        				}
        			}
            	});
                $("#example").dialog("open");
                return false;
            }
        );
        });
    });
</script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">Ketai Network Security Report</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist start**-->
            <div  class="head_content">
                <div class="actionBar mainHead" ></div>
                <div class="finder">
                    <div align="center">
                        <?php echo $data;?>
                    </div>
                </div>
            </div>

            <!--**productlist end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
            <ul>                
                <li>Ketai Network Security Report</li>
            </ul>
        </div><!--end of div class Turnpage_leftper-->

        <div class="Turnpage_rightper">

        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<div id='example'></div>
