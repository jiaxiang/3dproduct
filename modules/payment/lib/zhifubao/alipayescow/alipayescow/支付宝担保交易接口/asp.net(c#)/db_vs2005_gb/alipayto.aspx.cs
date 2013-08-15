using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Collections;
using AlipayClass;

/// <summary>
/// 功能：设置商品有关信息（入口页）
/// 详细：该页面是接口入口页面，生成支付时的URL
/// 版本：3.1
/// 日期：2010-11-24
/// 说明：
/// 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
/// 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
/// 
/// /////////////////注意///////////////////////////////////////////////////////////////
/// 如果您在接口集成过程中遇到问题，
/// 您可以到商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决，
/// 您也可以到支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）寻找相关解决方案
/// 
/// 如果不想使用扩展功能请把扩展功能参数赋空值。
/// 要传递的参数要么不允许为空，要么就不要出现在数组与隐藏控件或URL链接里。
/// </summary>
public partial class alipayto : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        ///////////////////////以下参数是需要设置的相关配置参数，设置后不会更改的///////////////////////////
        AlipayConfig con = new AlipayConfig();
        string partner = con.Partner;
        string key = con.Key;
        string seller_email = con.Seller_email;
        string input_charset = con.Input_charset;
        string notify_url = con.Notify_url;
        string return_url = con.Return_url;
        string show_url = con.Show_url;
        string sign_type = con.Sign_type;

        ////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////以下参数是需要通过下单时的订单数据传入进来获得////////////////////////////////
        //必填参数
        string out_trade_no = DateTime.Now.ToString("yyyyMMddHHmmss");  //请与贵网站订单系统中的唯一订单号匹配
        string subject = Request.Form["aliorder"];                      //订单名称，显示在支付宝收银台里的“商品名称”里，显示在支付宝的交易管理的“商品名称”的列表里。
        string body = Request.Form["alibody"];                          //订单描述、订单详细、订单备注，显示在支付宝收银台里的“商品描述”里
        string price = Request.Form["alimoney"];    		            //订单总金额，显示在支付宝收银台里的“商品单价”里

        string logistics_fee = "0.00";                  				//物流费用，即运费。
        string logistics_type = "EXPRESS";				                //物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        string logistics_payment = "SELLER_PAY";            			//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

        string quantity = "1";              							//商品数量，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品。

        //扩展参数——买家收货信息（推荐作为必填）
        //该功能作用在于买家已经在商户网站的下单流程中填过一次收货信息，而不需要买家在支付宝的付款流程中再次填写收货信息。
        //若要使用该功能，请至少保证receive_name、receive_address有值
        //收货信息格式请严格按照姓名、地址、邮编、电话、手机的格式填写
        string receive_name = "收货人姓名";			                    //收货人姓名，如：张三
        string receive_address = "收货人地址";			                //收货人地址，如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
        string receive_zip = "123456";                  			    //收货人邮编，如：123456
        string receive_phone = "0571-81234567";                		    //收货人电话号码，如：0571-81234567
        string receive_mobile = "13312341234";               		    //收货人手机号码，如：13312341234

        //扩展参数——第二组物流方式
        //物流方式是三个为一组成组出现。若要使用，三个参数都需要填上数据；若不使用，三个参数都需要为空
        //有了第一组物流方式，才能有第二组物流方式，且不能与第一个物流方式中的物流类型相同，
        //即logistics_type="EXPRESS"，那么logistics_type_1就必须在剩下的两个值（POST、EMS）中选择
        string logistics_fee_1 = "";                					//物流费用，即运费。
        string logistics_type_1 = "";               					//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        string logistics_payment_1 = "";           					    //物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

        //扩展参数——第三组物流方式
        //物流方式是三个为一组成组出现。若要使用，三个参数都需要填上数据；若不使用，三个参数都需要为空
        //有了第一组物流方式和第二组物流方式，才能有第三组物流方式，且不能与第一组物流方式和第二组物流方式中的物流类型相同，
        //即logistics_type="EXPRESS"、logistics_type_1="EMS"，那么logistics_type_2就只能选择"POST"
        string logistics_fee_2 = "";                					//物流费用，即运费。
        string logistics_type_2 = "";               					//物流类型，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        string logistics_payment_2 = "";            					//物流支付方式，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）

        //扩展功能参数——其他
        string buyer_email = "";                    					//默认买家支付宝账号
        string discount = "";                       					//折扣，是具体的金额，而不是百分比。若要使用打折，请使用负数，并保证小数点最多两位数

        /////////////////////////////////////////////////////////////////////////////////////////////////////

        //构造请求函数，无需修改
        AlipayService aliService = new AlipayService(
            partner,
            seller_email,
            return_url,
            notify_url,
            show_url,
            out_trade_no,
            subject,
            body,
            price,
            logistics_fee,
            logistics_type,
            logistics_payment,
            quantity,
            receive_name,
            receive_address,
            receive_zip,
            receive_phone,
            receive_mobile,
            logistics_fee_1,
            logistics_type_1,
            logistics_payment_1,
            logistics_fee_2,
            logistics_type_2,
            logistics_payment_2,
            buyer_email,
            discount,
            key,
            input_charset,
            sign_type);
        string sHtmlText = aliService.Build_Form();

        //打印页面
        lbOut_trade_no.Text = out_trade_no;
        lbTotal_fee.Text = price;
        lbButton.Text = sHtmlText;
    }
}
