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
using AlipayClass;
using System.Xml;
using System.Text;
using System.IO;
using System.Net;

/// <summary>
/// 功能：支付宝发货接口的入口页面，生成请求URL
/// 版本：3.1
/// 日期：2010-12-17
/// 说明：
/// 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
/// 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
/// 
/// /////////////////注意///////////////////////////////////////////////////////////////
/// 如果不想使用扩展功能请把扩展功能参数赋空值。
/// 该页面测试时出现“调试错误”请参考：http://club.alipay.com/read-htm-tid-8681712.html
/// 要传递的参数要么不允许为空，要么就不要出现在数组与隐藏控件或URL链接里。
/// 
/// 确认发货没有服务器异步通知页面（notify_url）与页面跳转同步通知页面（return_url），
/// 发货操作后，该笔交易的状态发生了变更，支付宝会主动发送通知给商户网站，而商户网站在担保交易或双功能的接口中的服务器异步通知页面（notify_url）
/// 该发货接口仅针对担保交易接口、双功能接口中的担保交易支付里涉及到需要卖家做发货的操作
/// 
/// 各家快递公司都属于EXPRESS（快递）的范畴
/// </summary>
public partial class sendgoods : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        ///////////////////////以下参数是需要设置的相关配置参数，设置后不会更改的////////////////////////////
        AlipayConfig con = new AlipayConfig();
        string partner = con.Partner;
        string key = con.Key;
        string input_charset = con.Input_charset;
        string sign_type = con.Sign_type;

        ////////////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////请求参数/////////////////////////////////////////////////////////////////////
        //--------------必填参数--------------
        //支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX） 
        string trade_no = Request.Form["trade_no"];

        //物流公司名称
        string logistics_name = Request.Form["logistics_name"];

        //物流发货单号
        string invoice_no = Request.Form["invoice_no"];

        //物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
        string transport_type = Request.Form["transport_type"];

        //--------------选填参数--------------
        //卖家本地电脑IP地址
        string seller_ip = "";

        /////////////////////////////////////////////////////////////////////////////////////////////////////

        //构造请求函数
        AlipayService aliService = new AlipayService(
            partner,
            trade_no,
            logistics_name,
            invoice_no,
            transport_type,
            seller_ip,
            key,
            input_charset,
            sign_type);

        string sHtmlText = aliService.Build_Form();
        Response.Write(sHtmlText);
    }
}
