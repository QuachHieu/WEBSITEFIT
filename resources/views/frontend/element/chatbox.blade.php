<style type="text/css">
			fieldset {
				border: 0;
				margin: 0;
				padding: 0;
			}

			h4, h5 {
				line-height: 1.5em;
				margin: 0;
			}

			hr {
				background: #e9e9e9;
			    border: 0;
			    -moz-box-sizing: content-box;
			    box-sizing: content-box;
			    height: 1px;
			    margin: 0;
			    min-height: 1px;
			}

			img {
			    border: 0;
			    display: block;
			    height: auto;
			    max-width: 100%;
			}

			input {
				border: 0;
				color: inherit;
			    font-family: inherit;
			    font-size: 100%;
			    line-height: normal;
			    margin: 0;
			}

			p { margin: 0; }

			.clearfix { *zoom: 1; } /* For IE 6/7 */
			.clearfix:before, .clearfix:after {
			    content: "";
			    display: table;
			}
			.clearfix:after { clear: both; }

			/* ---------- LIVE-CHAT ---------- */

			#live-chat {
				bottom: 0;
				font-size: 12px;
				right: 24px;
				position: fixed;
				width: 300px;
			}

			#live-chat header {
				background: #293239;
				border-radius: 5px 5px 0 0;
				color: #fff;
				cursor: pointer;
				padding: 16px 24px;
			}

			#live-chat h4:before {
				background: #1a8a34;
				border-radius: 50%;
				content: "";
				display: inline-block;
				height: 8px;
				margin: 0 8px 0 0;
				width: 8px;
			}

			#live-chat h4 {
				font-size: 12px;
			}

			#live-chat h5 {
				font-size: 10px;
			}

			#live-chat form {
				padding: 24px;
			}

			#live-chat input[type="text"] {
				border: 1px solid #ccc;
				border-radius: 3px;
				padding: 8px;
				outline: none;
				width: 234px;
			}

			.chat-message-counter {
				background: #e62727;
				border: 1px solid #fff;
				border-radius: 50%;
				display: none;
				font-size: 12px;
				font-weight: bold;
				height: 28px;
				left: 0;
				line-height: 28px;
				margin: -15px 0 0 -15px;
				position: absolute;
				text-align: center;
				top: 0;
				width: 28px;
			}

			.chat-close {
				background: #1b2126;
				border-radius: 50%;
				color: #fff;
				display: block;
				float: right;
				font-size: 10px;
				height: 16px;
				line-height: 16px;
				margin: 2px 0 0 0;
				text-align: center;
				width: 16px;
			}

			.chat {
				background: #fff;
			}

			.chat-history {
				height: 252px;
				padding: 8px 24px;
				overflow-y: scroll;
			}

			.chat-message {
				margin: 16px 0;
			}

			.chat-message img {
				border-radius: 50%;
				float: left;
			}

			.chat-message-content {
				margin-left: 56px;
			}

			.chat-time {
				float: right;
				font-size: 10px;
			}

			.chat-feedback {
				font-style: italic;	
				margin: 0 0 0 80px;
			}
			a { text-decoration: none; }
		</style>
@if(Auth::guard('admin')->user())
		<div id="live-chat">
		<header class="clearfix">

			<h4>Boxchat</h4>

			<span class="chat-message-counter">3</span>

		</header>

		<div class="chat">
			
			<div class="chat-history">

        @foreach($admins as $admin)
        @php 
            if($admin->id > Auth::guard('admin')->user()->id){
              $user_key = Auth::guard('admin')->user()->id.'_'.$admin->id;
            }else{
              $user_key = $admin->id.'_'.Auth::guard('admin')->user()->id;
            }
            $admin_receive = session('admin_receive_id') > 0 ? session('admin_receive_id') : '' ;
        @endphp
				<div class="chat-message clearfix" id="admin_active_{{ $admin->id }}" >
            <img src="{{ $admin->avatar !='' ? $admin->avatar : '/images/noavatar.png' }}" style="width: 32px; height: 32px" />
              <div class="chat-message-content clearfix">
				
								<span class="chat-time">{{ isset($array_message[$user_key]) ? $array_message[$user_key] : '' }}</span>

								<h5>{{ $admin->name }}</h5>
								
							</div> <!-- end chat-message-content -->

				</div> <!-- end chat-message -->

				<hr>
        @endforeach 
				

			</div> <!-- end chat-history -->




		</div> <!-- end chat -->

	</div> <!-- end live-chat -->
@endif	
    <script type="text/javascript">
	(function() {

	$('#live-chat header').on('click', function() {

		$('.chat').slideToggle(300, 'swing');
		$('.chat-message-counter').fadeToggle(300, 'swing');

	});

}) ();
</script>
<script>

function sendMessage(){
  //alert('AAAAAAAAA');
  document.getElementById("form-create").submit();
}

function checkMessage(id){
  const collection = document.getElementsByClassName("chat-message clearfix");
  for (let i = 0; i < collection.length; i++) {
    collection[i].classList.remove("active");
  }
  document.getElementById("admin_active_"+id).classList.add("active");
  $.ajax({
    url: '{{ route("frontend.cms.message.list") }}',
    type: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      id: id
    },
    context: document.body,
  }).done(function(data) {
    $('#content_message').html(data);
    $('#guitinnhan').attr('style','display:block');
    $('#admin_receive_id').val(id);
    $('#enter_message').focus();
  });
}
</script>