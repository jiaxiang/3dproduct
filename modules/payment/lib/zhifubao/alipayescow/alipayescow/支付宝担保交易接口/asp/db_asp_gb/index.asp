<%
	'���ܣ����ٸ������ģ��ҳ
	'��ϸ����ҳ������Բ��漰�����ﳵ���̡���ֵ���̵�ҵ�����̣�ֻ��Ҫʵ������ܹ����ٸ�������ҵĸ���ܡ�
	'�汾��3.1
	'���ڣ�2010-11-23
	'˵����
	'���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	'�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
%>
<!--#include file="alipay_Config.asp"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML XMLNS:CC><HEAD><TITLE>֧���� - ����֧�� ��ȫ���٣�</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<META content=���Ϲ���/����֧��/��ȫ֧��/��ȫ����/�����ȫ/֧��,��ȫ/֧����/��ȫ,֧��/��ȫ������/֧��, 
name=description ���� ����,�տ� ����,ó�� ����ó��.>
<META content=���Ϲ���/����֧��/��ȫ֧��/��ȫ����/�����ȫ/֧��,��ȫ/֧����/��ȫ,֧��/��ȫ������/֧��, name=keywords 
���� ����,�տ� ����,ó�� ����ó��.><LINK href="images/layout.css" 
type=text/css rel=stylesheet>

<SCRIPT language=JavaScript>
<!-- 
  //У�������  -->
function CheckForm()
{
	if (document.alipayment.aliorder.value.length == 0) {
		alert("��������Ʒ����.");
		document.alipayment.aliorder.focus();
		return false;
	}
	if (document.alipayment.alimoney.value.length == 0) {
		alert("�����븶����.");
		document.alipayment.alimoney.focus();
		return false;
	}
	var reg	= new RegExp(/^\d*\.?\d{0,2}$/);
	if (! reg.test(document.alipayment.alimoney.value))
	{
        alert("����ȷ���븶����");
		document.alipayment.alimoney.focus();
		return false;
	}
	if (Number(document.alipayment.alimoney.value) < 0.01) {
		alert("����������С��0.01.");
		document.alipayment.alimoney.focus();
		return false;
	}

}  

<!-- 
  //����������ʾ -->
function glowit(which){
if (document.all.glowtext[which].filters[0].strength==2)
document.all.glowtext[which].filters[0].strength=1
else
document.all.glowtext[which].filters[0].strength=2
}
function glowit2(which){
if (document.all.glowtext.filters[0].strength==2)
document.all.glowtext.filters[0].strength=1
else
document.all.glowtext.filters[0].strength=2
}
function startglowing(){
if (document.all.glowtext&&glowtext.length){
for (i=0;i<glowtext.length;i++)
eval('setInterval("glowit('+i+')",150)')
}
else if (glowtext)
setInterval("glowit2(0)",150)
}
if (document.all)
window.onload=startglowing


</SCRIPT>
</HEAD>
<style>
<!--
#glowtext{
filter:glow(color=red,strength=2);
width:100%;
}
-->
</style>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=4>
<CENTER>

<TABLE cellSpacing=0 cellPadding=0 width=805 border=0>
  <TR>
    <TD class=title>֧�����������׸������ͨ��</TD>
  </TR>
</TABLE><BR>
<FORM name=alipayment onSubmit="return CheckForm();" action=alipayto.asp 
method=post target="_blank">
<table>
 <tr>
   <td>
     <TABLE cellSpacing=0 cellPadding=0 width=600 border=0>
        <TR>
          <TD class=form-left>�տ�� </TD>
          <TD class=form-star>* </TD>
          <TD class=form-right><%=mainname%>&nbsp;</TD>
        </TR>
        <TR>
          <TD colspan="3" align="center"><HR width=600 SIZE=2 color="#999999"></TD>
        </TR>
        <TR>
          <TD class=form-left>���⣺ </TD>
          <TD class=form-star>* </TD>
          <TD class=form-right><INPUT size=30 name=aliorder maxlength="200"><span>�磺7��5�ն����</span></TD>
        </TR>
        <TR>
          <TD class=form-left>����� </TD>
          <TD class=form-star>*</TD>
          <TD class=form-right><INPUT maxLength=10 size=30 name=alimoney onfocus="if(Number(this.value)==0){this.value='';}" value="00.00"/>
            <span>�磺112.21</span></TD>
        </TR>
        <TR>
          <TD class=form-left>��ע��</TD>
          <TD class=form-star></TD>
          <TD class=form-right><TEXTAREA name=alibody rows=2 cols=40 wrap="physical"></TEXTAREA><BR>
          ������ϵ��������ƷҪ�������ȡ�100�����ڣ�</TD>
        </TR>
         <TR>
          <TD class=form-left></TD>
          <TD class=form-star></TD>
          <TD class=form-right><INPUT type=image 
            src="images/button_sure.gif" value=ȷ�϶��� 
            name=nextstep></TD>
        </TR>
</TABLE>
   </td>
   <td vAlign=top width=205 style="font-size:12px;font-family:'����'">
   <span id="glowtext">С��ʿ��</span>
   <fieldset>
      <P class=STYLE1>��ͨ��Ϊ<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>�ͻ�ר�ã�����֧�����������֧��ǰ�뱾��վ���һ�¡�</P>
      <P class="style2">�������<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>ȷ�Ϻö����ͻ�����ٸ�������ڿ��ٸ���ͨ����ġ����⡱��������������������ͱ�ע��������Ӧ�Ķ�����Ϣ��</P>
      <P class="style2 style3">&nbsp;</P>
      </fieldset>
   </td>
 </tr>
</table>

</FORM>

<TABLE cellSpacing=1 width=760 border=0>
  <TR>
    <TD><FONT class=note-help>�������������򡱰�ť������ʾ���Ѿ����ܡ�֧��������Э�顱��ͬ�������ҹ������Ʒ�� 
      <BR>
      �������β�����������Ʒ��¼���ϣ��������ҵ�˵���ͽ��ܵĸ��ʽ�����ұ���е���Ʒ��Ϣ��ȷ��¼�����Σ� 
  </FONT>
 </TD>
 </TR>
</TABLE>

<TABLE cellSpacing=0 cellPadding=0 width=760 align=center border=0>
  <TR align=middle>
    <TD class="txt12 lh15"><A href="http://china.alibaba.com/" 
      target=_blank>����Ͱ����¹�˾</A> | ֧������Ȩ���� 2004-2012</TD>
  </TR>
  <TR align=middle>
    <TD class="txt12 lh15"><IMG alt="֧����ͨ��������Ȩ����ȫ��֤�� " 
      src="images/logo_vbvv.gif" border=0><BR>֧����ͨ��������Ȩ����ȫ 
  ��֤��
    </TD>
  </TR>
</TABLE>
</BODY></HTML>
