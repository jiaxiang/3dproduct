
<ul>
    <li <?php (isset($mac)&&$mac=='payment') && print('class="on"');?>><a href='/order/order_doc/payment'>收款单</a></li>
    <li <?php (isset($mac)&&$mac=='refund') && print('class="on"');?>><a href='/order/order_doc/refund'>退款单</a></li>
    <li <?php (isset($mac)&&$mac=='ship') && print('class="on"');?>><a href='/order/order_doc/ship'>发货单</a></li>
    <li <?php (isset($mac)&&$mac=='return_product') && print('class="on"');?>><a href='/order/order_doc/return_product'>退货单</a></li>
</ul>