$(function(){
	SwapTab(".swap-tab","li",".swap-content","ul","on");//ͨ��Tab�л�
	startmarquee('announ',22,1,500,3000);//ͷ���������
	slide("#yp-slide","cur",447,201,1);//��ҳ����ͼ
	//ͨ��select�˵�
	$(".mouseover").each(function(i){
		var type = $(this).attr('type'),height = parseInt($(this).attr('heights')),position=parseInt($(this).attr('position')),
			 navSub = $('.sub'+type+'_'+i);
		$(this).bind("mouseenter",function(){
			var offset =$(this).offset();
				if(navSub.css("display") == "none"){
					if(position==true){
						navSub.css({"position":"absolute","z-index":"100",left:offset.left,top:offset.top+height}).show();
					}else{
						navSub.show();
					}
				}
		}).bind("mouseleave",function(){
			navSub.hide();
		});
		navSub.bind({
			mouseenter:function(){
				navSub.show();
			},
			mouseleave:function(){
				navSub.hide();
			}
		})
	})
	//��ҳ��ƷĿ¼ie6֧��
	if("\v"=="v") {
		if (!window.XMLHttpRequest) {
			var catitem = $(".cat-item");
			catitem.hover(function(){
				$(this).addClass("cat-item-hover");
			},function(){
				$(this).removeClass("cat-item-hover");
			})
		} 
	}
	/*ɸѡ�˵�չ������*/
	$("#PropSingle dd.AttrBox").each(function(){
		var len = $(this).children().length;
		if(len >10){
			$(this).before("<dd class='more cu'>ȫ��չ��</dd>");
			var category = $(this).children('a:gt(9)'),moreBtn = $(this).siblings(".more");
			category.hide();
			moreBtn.click(function(){
				if(category.is(":visible")){
					category.hide();
					moreBtn.removeClass("on").text("ȫ��չ��");
				}else{
					category.show();
					moreBtn.addClass("on").text("������ʾ");
				}
			})
		}
	})
	$(".input").blur(function(){$(this).removeClass('input-focus');});
	$(".input").focus(function(){$(this).addClass('input-focus')});
})