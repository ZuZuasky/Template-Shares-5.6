var minpwlength=6;var fairpwlength=8;var STRENGTH_SHORT=0;var STRENGTH_WEAK=1;var STRENGTH_FAIR=2;var STRENGTH_STRONG=3;img0=new Image();img1=new Image();img2=new Image();img3=new Image();img0.src=dimagedir+'ps/tooshort.jpg';img1.src=dimagedir+'ps/fair.jpg';img2.src=dimagedir+'ps/medium.jpg';img3.src=dimagedir+'ps/strong.jpg';var strengthlevel=0;var strengthimages=Array(img0.src,img1.src,img2.src,img3.src);function updatestrength(pw){if(istoosmall(pw)){strengthlevel=STRENGTH_SHORT;}
else if(!isfair(pw)){strengthlevel=STRENGTH_WEAK;}
else if(hasnum(pw)){strengthlevel=STRENGTH_STRONG;}
else{strengthlevel=STRENGTH_FAIR;}
document.getElementById('strength').src=strengthimages[strengthlevel];}
function isfair(pw){if(pw.length<fairpwlength){return false;}
else{return true;}}
function istoosmall(pw){if(pw.length<minpwlength){return true;}
else{return false;}}
function hasnum(pw){var hasnum=false;for(var counter=0;counter<pw.length;counter++){if(!isNaN(pw.charAt(counter))){hasnum=true;}}
return hasnum;}
function showimage(spanId,imageName){document.getElementById(spanId).innerHTML='<img src="'+dimagedir+'input_'+imageName+'.gif" border="0">';}
function validatesignup(){var invalid=" ";var minpassLength=6;var maxpassLength=40;var minusernameLength=3;var maxusernameLength=12;var pw1=document.signup.wantpassword.value;var pw2=document.signup.passagain.value;var username=document.signup.wantusername.value;if(username=='')
{alert(l_entername);showimage('username','error');document.signup.wantusername.focus();return false;}
if(document.signup.wantusername.value.length<minusernameLength||document.signup.wantusername.value.length>maxusernameLength){alert(''+l_wrongusername+' '+minusernameLength+' / '+maxusernameLength+'');showimage('username','error');document.signup.wantusername.focus();return false;}
if(document.signup.wantusername.value.indexOf(invalid)>-1){alert(l_spacenotallowed);showimage('username','error');document.signup.wantusername.value="";document.signup.wantusername.focus();return false;}
showimage('username','true');if(pw1==''||pw2==''){alert(l_passwordtwice);showimage('pass1','error');showimage('pass2','error');document.signup.wantpassword.focus();return false;}
if(document.signup.wantpassword.value.length<minpassLength||document.signup.wantpassword.value.length>maxpassLength){alert(''+l_wrongpassword1+' '+minpassLength+' / '+maxpassLength+'');showimage('pass1','error');showimage('pass2','error');document.signup.wantpassword.focus();return false;}
if(document.signup.wantpassword.value.indexOf(invalid)>-1){alert(l_spacenotallowed);showimage('pass1','error');showimage('pass2','error');document.signup.wantpassword.value="";document.signup.passagain.value="";document.signup.wantpassword.focus();return false;}
if(pw1!=pw2){alert(l_wrongpassword2);showimage('pass1','error');showimage('pass2','error');document.signup.wantpassword.value="";document.signup.passagain.value="";document.signup.wantpassword.focus();return false;}
if(username==pw1||username==pw2)
{alert(l_wrongpassword3);showimage('pass1','error');showimage('pass2','error');document.signup.wantpassword.value="";document.signup.passagain.value="";document.signup.wantpassword.focus();return false;}
showimage('pass1','true');showimage('pass2','true');if(!isValidEmail(document.signup.email.value))
{alert(l_wrongemail);showimage('useremail','error');document.signup.email.value="";document.signup.email.focus();return false;}
else
{showimage('useremail','true');document.signup.submit.value=l_pleasewait;document.signup.submit.disabled=true;return true;}}
function isValidEmail(email,required){if(required==undefined){required=true;}
if(email==null){if(required){return false;}
return true;}
if(email.length==0){if(required){return false;}
return true;}
if(!allValidChars(email)){return false;}
if(email.indexOf("@")<1){return false;}else if(email.lastIndexOf(".")<=email.indexOf("@")){return false;}else if(email.indexOf("@")==email.length){return false;}else if(email.indexOf("..")>=0){return false;}else if(email.indexOf(".")==email.length){return false;}
return true;}
function allValidChars(email){var parsed=true;var validchars="abcdefghijklmnopqrstuvwxyz0123456789@.-_";for(var i=0;i<email.length;i++){var letter=email.charAt(i).toLowerCase();if(validchars.indexOf(letter)!=-1)
continue;parsed=false;break;}
return parsed;}