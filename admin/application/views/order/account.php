<!--**content_frame start**-->
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <div class="clear"></div>
        <div class="public">
            <div class="public_left title_h3">
                <h3>账户</h3>
            </div>
        </div>
        <div class="actionBar mainHead">
        </div>
      <div class="clear"></div>
      <div class="main_content">
        <div class="tableform">
          <table cellspacing="0" cellpadding="0" border="0" width="30%">
            <tbody>
              <tr>
                <td width="33%"><h4>账户余额:<?php echo $data['account']['USD_amount'];?> USD</h4>
                  <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" class="finderInform" style="border-left:1px solid #dddddd;">
                      <thead>
                        <tr>
                          <th>币种</th>
                          <th>总计</th>
                        </tr>
                      </thead>
                      <tbody>
						<?php foreach((array)$data['balance'] as $key=>$balance){?>
                        <tr>
                          <td><?php echo $balance['currency'];?></td>
                          <td><?php echo $balance['amount'];?><?php echo $balance['currency'];?></td>
                        </tr>
						<?php }?>
                      </tbody>
                    </table>
                  </div></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tableform">
          <table cellspacing="0" cellpadding="0" border="0" width="70%">
            <tbody>
              <tr>
                <td width="33%"><h4>近期交易记录</h4>
                  <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" class="finderInform" style="border-left:1px solid #dddddd;">
                      <thead>
                        <tr>
                          <th>日期</th>
                          <th>类型</th>
                          <th>Email</th>
                          <th>名称</th>
                          <th>总额</th>
                          <th>详情</th>
                        </tr>
                      </thead>
                      <tbody>
						<?php foreach((array)$data['history'] as $key=>$history){?>
                        <tr>
                          <td><?php echo $history['date_add'];?></td>
                          <td><?php echo $history['type'];?></td>
                          <td><?php echo $history['email'];?></td>
                          <td><?php echo $history['name'];?></td>
                          <td><?php echo $history['amount'];?><?php echo $history['currency'];?></td>
                          <td><?php echo nl2br($history['message']);?></td>
                        </tr>
						<?php }?>
                      </tbody>
                    </table>
                  </div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--**content_frame end**-->
