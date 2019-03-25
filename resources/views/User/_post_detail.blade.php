  
  <div class="col-sm-7">  
  <div class="row">
    <div class=" well2" style="margin-left: 20px; margin-right: 20px">
      <div align="left">
        <table>
          <tr>
            <td style="padding-right: 20px">
              <img src="images/{{$diary->userAvatar($diary->id_user)}}.png" class="img-circle" height="55" width="55" alt="Avatar">
            </td>
            <td>
              <span class="page-newsfeed-06">
                <a href="profile/{{$diary->id_user}}" title="">{{$diary->userName($diary->id_user)}}</a>
              </span>
              <br> {{$diary->updated_at->diffForHumans()}} &nbsp;&nbsp;&nbsp;
              @if($diary->id_privacy == 0)
              <i class="fa fa-lock" aria-hidden="true"></i>
              @elseif($diary->id_privacy == 1)
              <i class="fa fa-users" aria-hidden="true"></i>
              @elseif($diary->id_privacy == 2)
              <i class="fa fa-cog" aria-hidden="true"></i>
              @else
              <i class="fa fa-globe " ></i>
              @endif

              &nbsp;&nbsp;&nbsp;
              <i class="fa fa-tag" ></i>
              Topic: <a class="tag badge badge-default overflow-hidden" data-v-2d5c6a76>{{$diary->category->name}}</a>
              &nbsp;&nbsp;&nbsp;
              @if($diary->id_user== Auth::user()->id)
              <a href="editPost/{{$diary->id}}" class="fa fa-pencil" data-toggle="modal" data-target="#editPost">edit</a>
              &nbsp;&nbsp;&nbsp;
              <a href="{{route('post.dele',['id' => $diary->id])}}" class="fa fa-trash">delete</a>
              @endif
            </td>
          </tr>
        </table>
      </div>
      <br>
      <img src="images/{{$diary->image}}.png" alt="Paris" width="100%" height="300">
      <h4 class="page-newsfeed-06"> {{$diary->title}}</h4> 
      <p>{{$diary->content}}</p> 
      
     

      <div class="panel-body timeline-resume">
                  <div class="pull-right" data-toggle="tooltip" title="" data-original-title="in this post">
                     @if($diary->likes->first() !== NULL)
                     @foreach ($diary->likes as $key => $like) 
                         
                      
                    <a class="kit-avatar kit-avatar-24 align-middle no-border" href="profile/{{$like->users->id}}">
                      <img alt="avatar" title="{{$like->users->name}}" src="/images/{{$user->userAvatar($like->users->id)}}.png" style="width: 25px;">
                    </a>
                 
                      @endforeach()
                       <!--<a href="#" class="btn btn-xs btn-default btn-circle">+{{$diary->likes->count()-1}}</a>-->
                     @endif
                  </div>
                  <div class="pull-left">
                      <a href="{{route('status.like',['statusId'=>$diary->id])}}" class="btn btn-bordered btn-default btn-sm">+{{$diary->likes->count()}}</a>
                      <a href="#" class="btn btn-bordered btn-default btn-sm"><span class="fa fa-share fa-fw"></span> 7</a>
                  </div>
      </div><!-- /.panel-body -->

      <br>
      {{-- <hr style="background-color: #000;height: 1px;"> 
      <div class="page-newsfeed-06">
          <table>
            <tr>
              <td width="70px">
                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> 100
              </td>
              <td width="50%">
                <i class="fa fa-comments-o" aria-hidden="true"></i> 
                {{count($diary->comment)}}
              </td>
            </tr>
          </table>
          </div> --}}
      {{-- comment --}}
     <div style="background-color: #f6f7f1">
     
      <table v-if="comments.length == 0" style="width: 100%;">
         @foreach($comment as $cmt)
        <tr >
          <td style="padding-right: 20px; margin-right: 1px;" width="100">
            <img src="images/{{$cmt->userAvatar($cmt->id_user)}}.png" class="img-circle" height="55" width="55" alt="Avatar" style="margin-top: 10px;">
          </td>
          <td align="left" valign="top" style="margin-left: 1px;">
            <span class="page-newsfeed-06" style="margin-left: -16px;">
              <a href="profile/{{$cmt->id_user}}" title="" >{{$cmt->userName($cmt->id_user)}}</a>
            </span>
            <span class="font-custom-01" style="font-size: 10px;margin-left: -16px;">
              <br> {{$cmt->updated_at->diffForHumans()}}
            </span>               
          </td>
        </tr>
        <tr >
         <td style="size: 5px;"></td>
          <td align ="left" valign="top" style="padding-left: -0px;">
            {{-- comment --}}
            {{$cmt->content}}
          </td>
        </tr>
         @endforeach 
      </table>
      <table v-if="comments != null" v-for="comment in comments" style="width: 100%;">
        <tr >
          <td style="padding-right: 20px; margin-right: 1px;" width="100">
            <img :src="'images/avatar'+comment.user.id+'.png'" class="img-circle" height="55" width="55" alt="Avatar" style="margin-top: 10px;">
          </td>
          <td align="left" valign="top" style="margin-left: 1px;">
            <span class="page-newsfeed-06" style="margin-left: -16px;">
              <a :href="'profile/'+comment.id_user" title="" >@{{comment.user.name}}</a>
            </span>
            <span class="font-custom-01" style="font-size: 10px;margin-left: -16px;">
              <br>@{{comment.updated_at}}
            </span>               
          </td>
        </tr>
        <tr >
         <td style="size: 5px;"></td>
          <td align ="left" valign="top" style="padding-left: -0px;">
            {{-- comment --}}
            @{{comment.content}}
          </td>
        </tr>
      </table>
           
      {{-- write comment --}}
      
       
        {{-- <label for="">write comment</label> --}}
        <table style="width: 100%;">
          <tr>
            <td style="padding-right: 20px" width="100">
              <img src="images/{{$user->userAvatar($user->id)}}.png" class="img-circle" height="55" width="55" alt="Avatar">
            </td>
            <td align="left">
            <a href="profile/{{Auth::user()->id}}" class="page-newsfeed-06">{{$user->name}}</a>  
            <input type="hidden" name="diary_id" value='{{ $diary->id }}' />
             <meta name="csrf-token" content="{{ csrf_token() }}"> 
            <input  id="Comment" type="text" class="form-control" style="width: 100%" placeholder="input comment" v-model="commentData" >
            
              <br>                       
            </td>
            <td width="80px">
              <button class="btn btn-primary" style="height: 33px" @click="addComment({{$diary->id}})" >Submit</button>
            </td>
          </tr>
          <tr>
            <td></td>
            <td align="left">
              {{-- comment --}}
              
            </td>
          </tr>
        </table><br>
     

      </div>
    </div>
  </div>
</div>



  <div class="modal fade" id="newPost" role="dialog">     
    <div class="modal-dialog">   

        <div class="modal-content">
          <div class="modal-header modal-edit-head">
            <center><h4 class="modal-title">Write Post</h4></center>
          </div>
          <div class="modal-body">
          <div class=" well" style="margin-left: 20px; margin-right: 20px">
            <form name="form1" action="createPost" method="POST" role="form" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="danhsach" class="form-control">
              <div id="clock">
                <img src=""alt="" id ='image1' style="height: 250px;width: 485px" >
              </div>
              <div class="form-group">
                <label for="telephone">Image :</label>
                <input type="file" id='getval' name="image">
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Title">
              </div>
              <div class="form-group">   
                <table width="100%">
                  <tr>
                    <td width="33%">
                      
                      <select name="category_select" id="drop_down" style="width: 100%;" class="form-control">
                        <option value="other" id="otherOption" onclick="showOther();">new Topic</option> 
                        @foreach($category as $cat)
                        <option value="{{$cat->id}}" onclick="hideOther();">{{$cat->name}}</option>
                        @endforeach    
                      </select>
                    </td>
                    <td width="33%">
                      <input type="text"  name="category" id="other" placeholder="Other"  placeholder="New Topic" class="form-control"/>
                    </td>
                    <td width="33%">
                      <select name="privacy_select" id="drop_down2" style="width: 100%;" class="form-control">
                        <option value="0">Only me</option>
                        <option value="1">Friends</option>
                        <option value="2">Specific Friends</option>
                        <option value="3">Public</option>     
                      </select>
                    </td>
                  </tr>
                </table>           


              </div>
              {{-- <button onclick="myFunction()">click</button> --}}
              <!-- <script>
                var dropdown = document.getElementById('drop_down');
              
                dropdown.onchange = function() {
                  var selected = dropdown.options[dropdown.selectedIndex].value;
              
                  switch(selected) {
                    case 'other':
                    document.getElementById('other').value = "";
                    document.getElementById('other').style.display = 'block'; 
                    document.getElementById('otherBR').style.display = 'block';
                    break;
                    default:
                    document.getElementById('other').style.display = 'none'; 
                    document.getElementById('otherBR').style.display = 'none';
                    break;
                  }
                }
              </script>
              <script>
                var dropdown = document.getElementById('drop_down2');
              
                dropdown.onchange = function() {
                  var selected = dropdown.options[dropdown.selectedIndex].value;
              
                  switch(selected) {
                    case "2":
                    $("#eventEdit").modal();
                    break;
                    default:                    
                    break;
                  }
                }
              </script> -->
              <textarea name="content" cols="73" rows="10"></textarea>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
          </div>
        </div>
      </div>      
  </div>
</div>

 <!-- Modal edit-->
    <div class="modal fade" id="eventEdit" role="dialog">
      <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header modal-edit-head">
            <center><h4 class="modal-title">Specific Friends</h4></center>
          </div>
          <div class="modal-body">
          <form name="MyForm">
            <div class="container-fluid edit-event-form">
              <div class="row">
                <div class="col-sm-12 edit-event-1">
                </div>
              </div>
            <div style="height: 400px;    overflow: scroll;">
            <br>
             <table width="100%">
             @foreach($user->friendList as $friend)
              <tr valign="top">
                <td style="padding-right: 20px;padding-bottom: 10px;width: 70px;">
                  <img src="images/{{$friend->userAvatar($friend->friend_id)}}.png" class="img-circle" height="55" width="55" alt="Avatar">
                </td>
                <td >
                  <a class="page-newsfeed-06" href="profile/{{$friend->friend_id}}">                  
                    {{$friend->userName($friend->friend_id)}}
                  </a>                 
                  
                </td>
                <td>
                  <input type="Checkbox" Name="MyCheckbox" value="{{$friend->friend_id}}" />
                </td>
              </tr>
              @endforeach
            </table>
            </div>
            </div>
            <button type="button" class="btn btn-primary " onclick="checkstatus()" data-dismiss="modal">Save Changes</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </form>
          <script language="javascript">
            function checkstatus()
            {
              var res= "0";
              for(count=0;count<4;count++)
              {
                if (MyForm.MyCheckbox[count].checked)
                  res=res + "-"+ MyForm.MyCheckbox[count].value;
                
              }
              form1.danhsach.value = res;
            }
          </script>
          </div>
        </div>      
      </div>
    </div>

<script>
  $(document).ready(function () {
    $('#image1').attr('src');
    console.log($('#image1').attr('src'));
    $('input[name=image]').on('change',function () {
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image1').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
      }
    })
  });
                var dropdown = document.getElementById('drop_down');

                dropdown.onchange = function() {
                  var selected = dropdown.options[dropdown.selectedIndex].value;

                  switch(selected) {
                    case 'other':
                    document.getElementById('other').value = "";
                    document.getElementById('other').style.display = 'block'; 
                    document.getElementById('otherBR').style.display = 'block';
                    break;
                    default:
                    document.getElementById('other').style.display = 'none'; 
                    document.getElementById('otherBR').style.display = 'none';
                    break;
                  }
                }
            
                var dropdown = document.getElementById('drop_down2');

                dropdown.onchange = function() {
                  var selected = dropdown.options[dropdown.selectedIndex].value;

                  switch(selected) {
                    case "2":
                    $("#eventEdit").modal();
                    break;
                    default:                    
                    break;
                  }
                }
               

</script>


 


