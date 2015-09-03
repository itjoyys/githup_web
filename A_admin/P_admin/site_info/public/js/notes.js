//联系小记加载更多

var page=1;
$(document).ready(function(){
    $('.notes_loaded_more').click(function(){
           var cid=$('.notes_loaded_more').attr('companyid');
           $.ajax({
					url : 'http://oa.wx0571.com/Admin/Work/notes_more/page/'+page+'/cid/'+cid,
					dataType : 'json',
					success: function(notes)
					{   
						
                         if (notes==0) {
                         	$('.notes_loaded_more').val('没有更多联系小记！');
                         }else{
                             for(var i=0, l=notes.length; i<l; i++)
					         {
						        oNotes = notes[i];
					        	$item = $('<div class="zt_con1"><dl><dt><h3>'+oNotes.company_notes+'</h3></dt></dl><ul><li><strong style="color: red;font-size: 12px;">'+oNotes.user_name+'</strong><strong style="color:red;">等级：'+oNotes.company_level+'</strong><strong style="font-size: 12px;">'+oNotes.notes_date+'</strong><p><a><img src="http://oa.wx0571.com/APP/Modules/Admin/Tpl/Public/Images/zt_f_57.gif"></a></p></li></ul></div>');
								 	
								$('#zt_con').append($item);
								
					        }
                         }
					},
            })
             page++;
  });
});
