/*
*�ı�����嵥��ͼƬ�¼���ͼƬ��ʾ
*/
function ChangStatus(){
	var player_list ;
	var list_array= new Array();
	player_list = getcookie('player_list');
 	if(player_list!=""){
		list_array = player_list.split("|");
		if(list_array.length>0){
 			for(var i=0;i<list_array.length;i++) {
				var player;
				player=list_array[i].split("@");  
				$("a[contentid='"+player[2]+"']").removeClass("j"); 
				$("a[contentid='"+player[2]+"']").addClass("j2");  
			}
		}
	} 
}

function toggle(object) { 
 	var url = $(object).attr("href");
	var title = $(object).attr("title");
	var id = $(object).attr("contentid");
	var catid = $(object).attr("catid");
   	var isCookieExist;   
     isCookieExist = getcookie('player_list');  
	if(!isCookieExist){
 		var new_player = url+'@'+title+'@'+id+'@'+catid;
		player_list = new_player;
	} else{
		var player_list = getcookie('player_list'); 
 		//�ж��Ƿ��Ѿ�����
 		if(player_list.indexOf(id)!=-1){
			//�Ѿ����벥�Ų˵�������COOKIE,ֱ����ת������ҳ��
   			var list_array = player_list.split("|"); 
			var player=new Array();

			for(var i=0;i<list_array.length;i++) {
 				player = list_array[i].split("@"); 
				if(id == player[2]){
					setcookie('now',i);
					window.open(player[0],'_blank');
				}
			}
  			return false;
		}  
		var new_player = '|'+url+'@'+title+'@'+id+'@'+catid;
		player_list +=new_player;
	}
 	//�ı�ͼƬ��ʽ������ȥ��onclick����
 	setcookie('player_list',player_list,1); 
	$("a[contentid='"+id+"']").removeClass("j"); 
	$("a[contentid='"+id+"']").addClass("j2"); 
	$("a[contentid='"+id+"']").unbind("click");  
	return false; 
} 