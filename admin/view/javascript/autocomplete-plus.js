/* AutocompletePlus 1.03 by AlexDW */

!function(t){t.fn.autocompleteplus=function(e){return this.each(function(){this.timer=null,this.items=new Array,t.extend(this,e),t(this).attr("autocomplete","off"),t(this).on("focus",function(){this.request()}),t(this).on("blur",function(){setTimeout(function(i){i.hide()},200,this)}),t(this).on("keydown",function(i){switch(i.keyCode){case 27:this.hide();break;default:this.request()}}),this.click=function(i){i.preventDefault(),value=t(i.target).parents("li").attr("data-value"),value&&this.items[value]&&this.select(this.items[value])},this.show=function(){var i=t(this).position();t(this).siblings("ul.dropdown-menu").css({top:i.top+t(this).outerHeight(),left:i.left}),t(this).siblings("ul.dropdown-menu").show()},this.hide=function(){t(this).siblings("ul.dropdown-menu").hide()},this.request=function(){clearTimeout(this.timer),this.timer=setTimeout(function(i){i.source(t(i).val(),t.proxy(i.response,i))},200,this)},this.response=function(e){if(html="",e.length){for(i=0;i<e.length;i++)this.items[e[i].value]=e[i];for(i=0;i<e.length;i++)html+='<li data-value="'+e[i].value+'"><a href="#">',0!=e[i].image&&(html+='<img class="img-thumbnail" style="max-width: none;" src="'+e[i].image+'"></img>'),html+="&nbsp;&nbsp;"+e[i].label+"&nbsp;&nbsp;",1==e[i].dfield&&""!=e[i].model&&(html+='<span style="color: #277EC2;">['+e[i].model+"]</span>"),1==e[i].dprice&&(0==e[i].special?html+='<span style="margin: 12px 0px 12px 10px; line-height: 1.6; padding: 2px 7px; border: 1px; border-radius: 2px; font-weight: bold; background-color: #277EC2; color: white;" >'+e[i].price+"</span>":html+='<span style="margin: 14px 0px 14px 10px; padding: 0px 5px; border: 1px; font-weight: bold; background-color: #F15F61; color: white; text-decoration: line-through;" >'+e[i].price+'</span><span style="margin: 12px 0px; line-height: 1.6; padding: 2px 7px; border: 1px; border-radius: 2px; font-weight: bold; background-color: #277EC2; color: white;" >'+e[i].special+"</span>"),html+="</a></li>"}html?this.show():this.hide(),t(this).siblings("ul.dropdown-menu").html(html)},t(this).after('<ul class="dropdown-menu"></ul>'),t(this).siblings("ul.dropdown-menu").delegate("a","click",t.proxy(this.click,this))})}}(window.jQuery);