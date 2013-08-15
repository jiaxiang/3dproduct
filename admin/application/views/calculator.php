<HTML><HEAD><TITLE>功能很全的精品计算器</TITLE> 
<META http-equiv=Content-Type content="text/html; charset=utf-8"> 
<META http-equiv=Content-Language content=zh-cn> 
<META http-equiv=Page-Enter content=blendTrans(Duration=0.5)> 
<META http-equiv=Page-Exit content=blendTrans(Duration=0.5)> 
<STYLE type=text/css>
P { 
 FONT-SIZE: 9pt; FONT-FAMILY: "Verdana" 
} 
TD { 
 FONT-SIZE: 9pt; LINE-HEIGHT: normal 
} 
A { 
 FONT-SIZE: 9pt; TEXT-TRANSFORM: none; COLOR: #326969; TEXT-DECORATION: none 
} 
A:hover { 
 FONT-SIZE: 9pt; LEFT: 1px; COLOR: #0000ff; POSITION: relative; TOP: 1px; TEXT-DECORATION: none 
} 
BODY { 
 FONT-SIZE: 9pt; CURSOR: url('body.cur'); SCROLLBAR-ARROW-COLOR: #ffffff; SCROLLBAR-BASE-COLOR: #cde0c2 
} 
.diary_tb { 
 WIDTH: 100%; WORD-BREAK: break-all; FONT-FAMILY: "Verdana","宋体"; POSITION: relative; TEXT-ALIGN: center; WORD-WRAP: break-word 
} 
.diary_main { 
 TABLE-LAYOUT: fixed; BACKGROUND-POSITION: left top; FONT-SIZE: 12px; COLOR: #005782; WORD-BREAK: break-all; LINE-HEIGHT: 24px; FONT-FAMILY: "宋体"; LINE-BREAK: strict; HEIGHT: 2px; TEXT-ALIGN: left 
} 
</STYLE> 

</HEAD> 
<CENTER> 
<TABLE id=AutoNumber2 style="BORDER-COLLAPSE: collapse" borderColor=#808080  
cellSpacing=0 cellPadding=0 width=760 border=0> 
</TABLE> 
<SCRIPT language=javascript> 
<!-- 
var endNumber=true 
var mem=0 
var carry=10 
var hexnum="0123456789abcdef" 
var angle="d" 
var stack="" 
var level="0" 
var layer=0 


//数字键 

function inputkey(key) 
{ 
var index=key.charCodeAt(0); 
if ((carry==2 && (index==48 || index==49)) 
|| (carry==8 && index>=48 && index<=55) 
|| (carry==10 && (index>=48 && index<=57 || index==46)) 
|| (carry==16 && ((index>=48 && index<=57) || (index>=97 && index<=102)))) 
if(endNumber) 
{ 
endNumber=false 
document.calc.display.value = key 
} 
else if(document.calc.display.value == null || document.calc.display.value == "0") 
document.calc.display.value = key 
else 
document.calc.display.value += key 
} 

function changeSign() 
{ 
if (document.calc.display.value!="0") 
if(document.calc.display.value.substr(0,1) == "-") 
document.calc.display.value = document.calc.display.value.substr(1) 
else 
document.calc.display.value = "-" + document.calc.display.value 
} 

//函数键 

function inputfunction(fun,shiftfun) 
{ 
endNumber=true 
if (document.calc.shiftf.checked) 
document.calc.display.value=decto(funcalc(shiftfun,(todec(document.calc.display.value,carry))),carry) 
else 
document.calc.display.value=decto(funcalc(fun,(todec(document.calc.display.value,carry))),carry) 
document.calc.shiftf.checked=false 
document.calc.hypf.checked=false 
inputshift() 
} 

function inputtrig(trig,arctrig,hyp,archyp) 
{ 
if (document.calc.hypf.checked) 
inputfunction(hyp,archyp) 
else 
inputfunction(trig,arctrig) 
} 


//运算符 

function operation(join,newlevel) 
{ 
endNumber=true 
var temp=stack.substr(stack.lastIndexOf("(")+1)+document.calc.display.value 
while (newlevel!=0 && (newlevel<=(level.charAt(level.length-1)))) 
{ 
temp=parse(temp) 
level=level.slice(0,-1) 
} 
if (temp.match(/^(.*\d[\+\-\*\/\%\^\&\|x])?([+-]?[0-9a-f\.]+)$/)) 
document.calc.display.value=RegExp.$2 
stack=stack.substr(0,stack.lastIndexOf("(")+1)+temp+join 
document.calc.operator.value=" "+join+" " 
level=level+newlevel 

} 

//括号 

function addbracket() 
{ 
endNumber=true 
document.calc.display.value=0 
stack=stack+"(" 
document.calc.operator.value="   " 
level=level+0 

layer+=1 
document.calc.bracket.value="(="+layer 
} 

function disbracket() 
{ 
endNumber=true 
var temp=stack.substr(stack.lastIndexOf("(")+1)+document.calc.display.value 
while ((level.charAt(level.length-1))>0) 
{ 
temp=parse(temp) 
level=level.slice(0,-1) 
} 

document.calc.display.value=temp 
stack=stack.substr(0,stack.lastIndexOf("(")) 
document.calc.operator.value="   " 
level=level.slice(0,-1) 

layer-=1 
if (layer>0) 
document.calc.bracket.value="(="+layer 
else 
document.calc.bracket.value="" 
} 

//等号 

function result() 
{ 
endNumber=true 
while (layer>0) 
disbracket() 
var temp=stack+document.calc.display.value 
while ((level.charAt(level.length-1))>0) 
{ 
temp=parse(temp) 
level=level.slice(0,-1) 
} 

document.calc.display.value=temp 
document.calc.bracket.value="" 
document.calc.operator.value="" 
stack="" 
level="0" 
} 


//修改键 

function backspace() 
{ 
if (!endNumber) 
{ 
if(document.calc.display.value.length>1) 
document.calc.display.value=document.calc.display.value.substring(0,document.calc.display.value.length - 1) 
else 
document.calc.display.value=0 
} 
} 

function clearall() 
{ 
document.calc.display.value=0 
endNumber=true 
stack="" 
level="0" 
layer="" 
document.calc.operator.value="" 
document.calc.bracket.value="" 
} 


//转换键 

function inputChangCarry(newcarry) 
{ 
endNumber=true 
document.calc.display.value=(decto(todec(document.calc.display.value,carry),newcarry)) 
carry=newcarry 

document.calc.sin.disabled=(carry!=10) 
document.calc.cos.disabled=(carry!=10) 
document.calc.tan.disabled=(carry!=10) 
document.calc.bt.disabled=(carry!=10) 
document.calc.pi.disabled=(carry!=10) 
document.calc.e.disabled=(carry!=10) 
document.calc.kp.disabled=(carry!=10) 

document.calc.k2.disabled=(carry<=2) 
document.calc.k3.disabled=(carry<=2) 
document.calc.k4.disabled=(carry<=2) 
document.calc.k5.disabled=(carry<=2) 
document.calc.k6.disabled=(carry<=2) 
document.calc.k7.disabled=(carry<=2) 
document.calc.k8.disabled=(carry<=8) 
document.calc.k9.disabled=(carry<=8) 
document.calc.ka.disabled=(carry<=10) 
document.calc.kb.disabled=(carry<=10) 
document.calc.kc.disabled=(carry<=10) 
document.calc.kd.disabled=(carry<=10) 
document.calc.ke.disabled=(carry<=10) 
document.calc.kf.disabled=(carry<=10) 

  

} 

function inputChangAngle(angletype) 
{ 
endNumber=true 
angle=angletype 
if (angle=="d") 
document.calc.display.value=radiansToDegress(document.calc.display.value) 
else 
document.calc.display.value=degressToRadians(document.calc.display.value) 
endNumber=true 
} 

function inputshift() 
{ 
if (document.calc.shiftf.checked) 
{ 
document.calc.bt.value="deg " 
document.calc.ln.value="exp " 
document.calc.log.value="expd" 

if (document.calc.hypf.checked) 
{ 
document.calc.sin.value="ahs " 
document.calc.cos.value="ahc " 
document.calc.tan.value="aht " 
} 
else 
{ 
document.calc.sin.value="asin" 
document.calc.cos.value="acos" 
document.calc.tan.value="atan" 
} 

document.calc.sqr.value="x^.5" 
document.calc.cube.value="x^.3" 

document.calc.floor.value="小数" 
} 
else 
{ 
document.calc.bt.value="d.ms" 
document.calc.ln.value=" ln " 
document.calc.log.value="log " 

if (document.calc.hypf.checked) 
{ 
document.calc.sin.value="hsin" 
document.calc.cos.value="hcos" 
document.calc.tan.value="htan" 
} 
else 
{ 
document.calc.sin.value="sin " 
document.calc.cos.value="cos " 
document.calc.tan.value="tan " 
} 

document.calc.sqr.value="x^2 " 
document.calc.cube.value="x^3 " 

document.calc.floor.value="取整" 
} 

} 
//存储器部分 

function clearmemory() 
{ 
mem=0 
document.calc.memory.value="   " 
} 

function getmemory() 
{ 
endNumber=true 
document.calc.display.value=decto(mem,carry) 
} 

function putmemory() 
{ 
endNumber=true 
if (document.calc.display.value!=0) 
{ 
mem=todec(document.calc.display.value,carry) 
document.calc.memory.value=" M " 
} 
else 
document.calc.memory.value="   " 
} 

function addmemory() 
{ 
endNumber=true 
mem=parseFloat(mem)+parseFloat(todec(document.calc.display.value,carry)) 
if (mem==0) 
document.calc.memory.value="   " 
else 
document.calc.memory.value=" M " 
} 

function multimemory() 
{ 
endNumber=true 
mem=parseFloat(mem)*parseFloat(todec(document.calc.display.value,carry)) 
if (mem==0) 
document.calc.memory.value="   " 
else 
document.calc.memory.value=" M " 
} 

//十进制转换 

function todec(num,oldcarry) 
{ 
if (oldcarry==10 || num==0) return(num) 
var neg=(num.charAt(0)=="-") 
if (neg) num=num.substr(1) 
var newnum=0 
for (var index=1;index<=num.length;index++) 
newnum=newnum*oldcarry+hexnum.indexOf(num.charAt(index-1)) 
if (neg) 
newnum=-newnum 
return(newnum) 
} 

function decto(num,newcarry) 
{ 
var neg=(num<0) 
if (newcarry==10 || num==0) return(num) 
num=""+Math.abs(num) 
var newnum="" 
while (num!=0) 
{ 
newnum=hexnum.charAt(num%newcarry)+newnum 
num=Math.floor(num/newcarry) 
} 
if (neg) 
newnum="-"+newnum 
return(newnum) 
} 

//表达式解析 

function parse(string) 
{ 
if (string.match(/^(.*\d[\+\-\*\/\%\^\&\|x\<])?([+-]?[0-9a-f\.]+)([\+\-\*\/\%\^\&\|x\<])([+-]?[0-9a-f\.]+)$/)) 
return(RegExp.$1+cypher(RegExp.$2,RegExp.$3,RegExp.$4)) 
else 
return(string) 
} 

//数学运算和位运算 

function cypher(left,join,right) 
{ 
left=todec(left,carry) 
right=todec(right,carry) 
if (join=="+") 
return(decto(parseFloat(left)+parseFloat(right),carry)) 
if (join=="-") 
return(decto(left-right,carry)) 
if (join=="*") 
return(decto(left*right,carry)) 
if (join=="/" && right!=0) 
return(decto(left/right,carry)) 
if (join=="%") 
return(decto(left%right,carry)) 
if (join=="&") 
return(decto(left&right,carry)) 
if (join=="|") 
return(decto(left|right,carry)) 
if (join=="^") 
return(decto(Math.pow(left,right),carry)) 
if (join=="x") 
return(decto(left^right,carry)) 
if (join=="<") 
return(decto(left<<right,carry)) 
alert("除数不能为零") 
return(left) 
} 

//函数计算 

function funcalc(fun,num) 
{ 
with(Math) 
{ 
if (fun=="pi") 
return(PI) 
if (fun=="e") 
return(E) 

if (fun=="abs") 
return(abs(num)) 
if (fun=="ceil") 
return(ceil(num)) 
if (fun=="round") 
return(round(num)) 

if (fun=="floor") 
return(floor(num)) 
if (fun=="deci") 
return(num-floor(num)) 


if (fun=="ln" && num>0) 
return(log(num)) 
if (fun=="exp") 
return(exp(num)) 
if (fun=="log" && num>0) 
return(log(num)*LOG10E) 
if (fun=="expdec") 
return(pow(10,num)) 


if (fun=="cube") 
return(num*num*num) 
if (fun=="cubt") 
return(pow(num,1/3)) 
if (fun=="sqr") 
return(num*num) 
if (fun=="sqrt" && num>=0) 
return(sqrt(num)) 

if (fun=="!") 
return(factorial(num)) 

if (fun=="recip" && num!=0) 
return(1/num) 

if (fun=="dms") 
return(dms(num)) 
if (fun=="deg") 
return(deg(num)) 

if (fun=="~") 
return(~num) 

if (angle=="d") 
{ 
if (fun=="sin") 
return(sin(degressToRadians(num))) 
if (fun=="cos") 
return(cos(degressToRadians(num))) 
if (fun=="tan") 
return(tan(degressToRadians(num))) 

if (fun=="arcsin" && abs(num)<=1) 
return(radiansToDegress(asin(num))) 
if (fun=="arccos" && abs(num)<=1) 
return(radiansToDegress(acos(num))) 
if (fun=="arctan") 
return(radiansToDegress(atan(num))) 
} 
else 
{ 
if (fun=="sin") 
return(sin(num)) 
if (fun=="cos") 
return(cos(num)) 
if (fun=="tan") 
return(tan(num)) 

if (fun=="arcsin" && abs(num)<=1) 
return(asin(num)) 
if (fun=="arccos" && abs(num)<=1) 
return(acos(num)) 
if (fun=="arctan") 
return(atan(num)) 
} 

if (fun=="hypsin") 
return((exp(num)-exp(0-num))*0.5) 
if (fun=="hypcos") 
return((exp(num)+exp(-num))*0.5) 
if (fun=="hyptan") 
return((exp(num)-exp(-num))/(exp(num)+exp(-num))) 

if (fun=="ahypsin" | fun=="hypcos" | fun=="hyptan") 
{ 
alert("对不起,公式还没有查到!") 
return(num) 
} 

alert("超出函数定义范围") 
return(num) 
} 
} 

function factorial(n) 
{ 
n=Math.abs(parseInt(n)) 
var fac=1 
for (;n>0;n-=1) 
fac*=n 
return(fac) 
} 

function dms(n) 
{ 
var neg=(n<0) 
with(Math) 
{ 
n=abs(n) 
var d=floor(n) 
var m=floor(60*(n-d)) 
var s=(n-d)*60-m 
} 
var dms=d+m/100+s*0.006 
if (neg) 
dms=-dms 
return(dms) 
} 

function deg(n) 
{ 
var neg=(n<0) 
with(Math) 
{ 
n=abs(n) 
var d=floor(n) 
var m=floor((n-d)*100) 
var s=(n-d)*100-m 
} 
var deg=d+m/60+s/36 
if (neg) 
deg=-deg 
return(deg) 
} 

function degressToRadians(degress) 
{ 
return(degress*Math.PI/180) 
} 

function radiansToDegress(radians) 
{ 
return(radians*180/Math.PI) 
} 

//界面 

//--> 
  </SCRIPT> 

<DIV align=center> 
<CENTER> 
<TABLE id=AutoNumber2 style="BORDER-COLLAPSE: collapse" borderColor=#808080  
cellSpacing=0 cellPadding=0 width=760 border=0> 
  <TBODY> 
  <TR> 
    <TABLE id=AutoNumber5 style="BORDER-COLLAPSE: collapse"  
      borderColor=#808080 height=30 cellSpacing=0 cellPadding=0 width="93%"  
      border=0> 
        <TBODY> 
          <TD align=middle width="100%" bgColor=#dbe8d2 height=30> 
            <P>　 
            <DIV align=center> 
            <FORM name=calc> 
            <TABLE height=250 width=500 border=0> 
              <TBODY> 
              <TR> 
                <TD bgColor=#ecf4ec height=50> 
                  <TABLE width=500> 
                    <TBODY> 
                    <TR> 
                      <TD width=486> 
                        <DIV align=center><INPUT readOnly  
                        size=67 value=0  
                  name=display></DIV></TD></TR></TBODY></TABLE></TD></TR> 
              <TR> 
                <TD bgColor=#ecf4ec> 
                  <TABLE width=500> 
                    <TBODY> 
                    <TR> 
                      <TD width=290><INPUT  
                        onclick=inputChangCarry(16) type=radio name=carry> 十六进制  
                        <INPUT onclick=inputChangCarry(10) type=radio CHECKED  
                        name=carry> 十进制 <INPUT onclick=inputChangCarry(8)  
                        type=radio name=carry> 八进制 <INPUT  
                        onclick=inputChangCarry(2) type=radio name=carry> 二进制  
                        </TD> 
                      <TD></TD> 
                      <TD width=135><INPUT  
                        onclick="inputChangAngle('d')" type=radio CHECKED  
                        value=d name=angle> 角度制 <INPUT  
                        onclick="inputChangAngle('r')" type=radio value=r  
                        name=angle> 弧度制 </TD></TR></TBODY></TABLE> 
                  <TABLE width=500> 
                    <TBODY> 
                    <TR> 
                      <TD width=170><INPUT  
                        onclick=inputshift() type=checkbox value=ON  
                        name=shiftf>上档功能 <INPUT onclick=inputshift()  
                        type=checkbox value=ON name=hypf>双曲函数 </TD> 
                      <TD><INPUT  
                        style="BACKGROUND-COLOR: lightgrey" readOnly size=3  
                        name=bracket> <INPUT style="BACKGROUND-COLOR: lightgrey"  
                        readOnly size=3 name=memory> <INPUT  
                        style="BACKGROUND-COLOR: lightgrey" readOnly size=3  
                        name=operator> </TD> 
                      <TD width=183><INPUT style="COLOR: red" onclick=backspace() type=button value=" 退格 ">  
<INPUT style="COLOR: red" onclick="document.calc.display.value = 0 " type=button value=" 清屏 ">  
<INPUT style="COLOR: red" onclick=clearall() type=button value=" 全清">  
                        </TD></TR></TBODY></TABLE> 
                  <TABLE width=500> 
                    <TBODY> 
                    <TR> 
                      <TD> 
                        <TABLE> 
                          <TBODY> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" onclick="inputfunction('pi','pi')" type=button value=" PI " name=pi>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputfunction('e','e')" type=button value=" E  " name=e>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('dms','deg')" type=button value=d.ms name=bt>  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick=addbracket() type=button value=" (  ">  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick=disbracket() type=button value=" )  ">  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('ln','exp')" type=button value=" ln " name=ln>  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputtrig('sin','arcsin','hypsin','ahypsin')" type=button value="sin " name=sin>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="operation('^',7)" type=button value="x^y ">  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('log','expdec')" type=button value="log " name=log>  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputtrig('cos','arccos','hypcos','ahypcos')" type=button value="cos " name=cos>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('cube','cubt')" type=button value="x^3 " name=cube>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('!','!')" type=button value=" n! ">  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputtrig('tan','arctan','hyptan','ahyptan')" type=button value="tan " name=tan>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('sqr','sqrt')" type=button value="x^2 " name=sqr>  
                              </TD> 
                            <TD><INPUT style="COLOR: #ff00ff" onclick="inputfunction('recip','recip')" type=button value="1/x ">  
                              </TD></TR></TBODY></TABLE></TD> 
                      <TD width=30></TD> 
                      <TD> 
                        <TABLE> 
                          <TBODY> 
                          <TR> 
                            <TD><INPUT style="COLOR: red" onclick=putmemory() type=button value=" 储存 ">  
                              </TD></TR> 
                          <TR> 
                            <TD><INPUT style="COLOR: red" onclick=getmemory() type=button value=" 取存 ">  
                              </TD></TR> 
                          <TR> 
                            <TD><INPUT style="COLOR: red" onclick=addmemory() type=button value=" 累存 ">  
                              </TD></TR> 
                          <TR> 
                            <TD><INPUT style="COLOR: red" onclick=multimemory() type=button value=" 积存 ">  
                              </TD></TR> 
                          <TR> 
                            <TD height=33><INPUT style="COLOR: red" onclick=clearmemory() type=button value=" 清存 ">  
                              </TD></TR></TBODY></TABLE></TD> 
                      <TD width=30></TD> 
                      <TD> 
                        <TABLE> 
                          <TBODY> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('7')" type=button value=" 7 " name=k7>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('8')" type=button value=" 8 " name=k8>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('9')" type=button value=" 9 " name=k9>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('/',6)" type=button value=" / ">  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('%',6)" type=button value=取余>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('&',3)" type=button value=" 与 ">  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('4')" type=button value=" 4 " name=k4>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('5')" type=button value=" 5 " name=k5>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('6')" type=button value=" 6 " name=k6>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('*',6)" type=button value=" * ">  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="inputfunction('floor','deci')" type=button value=取整 name=floor>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('|',1)" type=button value=" 或 ">  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('1')" type=button value=" 1 ">  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('2')" type=button value=" 2 " name=k2>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('3')" type=button value=" 3 " name=k3>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('-',5)" type=button value=" - ">  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('<',4)" type=button value=左移>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="inputfunction('~','~')" type=button value=" 非 ">  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('0')" type=button value=" 0 ">  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick=changeSign() type=button value=+/->  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" onclick="inputkey('.')" type=button value=" . " name=kp>  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('+',5)" type=button value=" + ">  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick=result() type=button value=" ＝ ">  
                              </TD> 
                            <TD><INPUT style="COLOR: red" onclick="operation('x',2)" type=button value=异或>  
                              </TD></TR> 
                          <TR align=middle> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('a')" type=button value=" A " name=ka>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('b')" type=button, value=" B " name=kb>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('c')" type=button value=" C " name=kc>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('d')" type=button value=" D " name=kd>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('e')" type=button value=" E　" name=ke>  
                              </TD> 
                            <TD><INPUT style="COLOR: blue" disabled onclick="inputkey('f')" type=button value=" F　" name=kf>  
                              </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE></FORM></DIV> 
            </CENTER></BODY></HTML> 