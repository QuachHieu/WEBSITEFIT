@include('frontend.element.styleboxchat')
@if(Auth::guard('admin')->user())

<div class="chatbox-holder">
  <div id="chatlist" style="display: none;">
    {{-- danh sách tin nhắn --}}
</div>

<style>
.count_mess{
  margin-left: 20px;
  width: 19px;
    height: 19px;
    line-height: 19px;
   
    /* background: #f12424; */
    color: red;
    /* position: absolute; */
    bottom: 6px;
    text-align: center;
    left: 17px;
    /* border-radius: 100%; */
    /* -webkit-border-radius: 100%; */
    font-weight: 600;
}
</style>
  <div class="chatboxx chatboxlist">
    <div class="chatbox-top">
      <div class="chat-partner-name">
        Danh sách liên hệ
      </div>
      <div class="chatbox-icons">
        <a href="javascript:void(0);"><i class="min fa fa-minus"></i></a>    
      </div>      
    </div>      
    <div class="chat-messages">
      @foreach($admins as $admin)
        @php 
            if($admin->id > Auth::guard('admin')->user()->id){
              $user_key = Auth::guard('admin')->user()->id.'_'.$admin->id;
            }else{
              $user_key = $admin->id.'_'.Auth::guard('admin')->user()->id;
            }
            $admin_receive = session('admin_receive_id') > 0 ? session('admin_receive_id') : '' ;
        @endphp
        <div class="chat-messages-list" id="admin_active_{{ $admin->id }}" >
          <input type="hidden" id="id_user" value="{{ Auth::guard('admin')->user()->id }}">
          <input type="hidden" id="id_list" value="{{ $admin->id }}">
            <img src="{{ $admin->avatar !='' ? $admin->avatar : '/images/noavatar.png' }}" style="width: 32px; height: 32px" />
              <div class="chat-message-content clearfix" style="padding-left: 10px;margin-top: 5px;">

                <a onclick="boxchat('{{ $admin->id }}','{{ $admin->name }}')" style="cursor:pointer;">{{ $admin->name }}</a>

                  <span class="count_mess" id="count_mess_{{ $admin->id }}">
                  
                  </span>
                  <audio id="audio" src="{{asset('themes/frontend/cdts/message.mp3')}}" controls style="display : none;"></audio>
              
              </div> <!-- end chat-message-content -->

        </div> <!-- end chat-message -->
        @endforeach
    </div>
  </div>
</div>

 @endif
@if(Auth::guard('admin')->user())
<script type="text/javascript">
  function boxchat(id_list,name_list){

    document.getElementById("chatlist").style.display = "block";

      var id_user = $("#id_user").val();
      var f = "?id_list=" + id_list+  "&id_user=" + id_user + "&name_list=" +name_list;

      var _url = "/listchat" + f;

      $.ajax({
        type: "GET",
        url: _url,
        data: f,
        cache: false,
        context: document.body,
        success: function(data) {

          $("#chatlist").html(data);

        }
      });
  }

</script>

<script type="text/javascript">
  function countmess(){
   
    var _url = "/countmess" ;

      $.ajax({
        type: "GET",
        url: _url,
        cache: false,
        context: document.body,
        success: function(data) {
        const count_mess = data.split(';');

        count_mess.forEach(
          function myFunction(item, index, arr) 
          {
          arr[index] = item.split('');
          $("#count_mess_"+arr[index][0]).html(arr[index][2]);
          
          }
        ); 
       
        //  $("#count_mess_"+count_mess[0][0]).html(count_mess[0][2]);
         
        }
      });
  }
  $(document).ready(function() {
      setInterval(countmess, 2000);
  });
  countmess();
</script>

<script>
    function sound(){
      var _url = "/sound" ;

      $.ajax({
        type: "GET",
        url: _url,
        cache: false,
        context: document.body,
        success: function(data) {
          alert(data);
          // if(data==0)
          // $("#audio").play();         
        }
      });
  }
    
</script>


@endif
<script type="text/javascript">
  $(function(){

  $('.fa-minus').click(function(){    
    $(this).closest('.chatbox').toggleClass('chatbox-min');
  });

  $('.min').click(function(){    
    $(this).closest('.chatboxx').toggleClass('chatbox-min');
  });

  $('.fa-close').click(function(){
    $(this).closest('.chatbox').hide();
  });
});
</script>
