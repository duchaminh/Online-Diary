<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use App\Event;
use App\Category;
use App\Diary;
use App\Comment;
use App\Friend;
use App\SpecificFriend;
use App\Notification;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

	public function home(){
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$friendRequests= Friend::where("friend_id",Auth::user()->id)
                            ->where("accepted",0)
                            ->get();
		$user= User::find(Auth::user()->id);
		$diaries= Diary::all();
		$friend= $user->friendList;
		$category= $user->category;
		return view('home',[
			'friends'=>$friend,
			'friendRequests'=>$friendRequests,
			'notifications'=>$notifications,
			'category'=>$category,
			'diaries'=>$diaries,
			'user'=>$user
			]);
	}
	public function test(){
		$f= Friend::find(5);
		//echo $f->user_id;
		echo $f->userAvatar($f->user_id);
	}

	public function showNewPost(){
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$friendRequests= Friend::where("friend_id",Auth::user()->id)
                            ->where("accepted",0)
                            ->get();
		$user= User::where('id',Auth::user()->id)->first();
    	//echo $user;
		$category= $user->category;
    	//echo $category;
		return view('User.writePost',['category'=>$category,
			'friendRequests'=>$friendRequests,
			'notifications'=>$notifications,
			'user'=>$user
			]);
	}
	public function createPost(Request $request){
		$v = Validator::make($request->all(),[
    		'title' => 'required|max:50',
    		'content' => 'required'
    	]);

    	if($v->fails())
    		return redirect()->back()->withErrors($v->errors());

		$user_id=Auth::user()->id;
		$str="".$request['category'];
		$id_category=0;
		if(strlen($str)!=0){
			$category= new Category();
			$category->name= $request['category'];
			$category->id_user=$user_id;
			$category->save();
			$id_category=$category->id;
		}
		else {
			$id_category= $request['category_select'];
		}

		$diary= new Diary();
		$diary->id_user= $user_id;
		$diary->title= $request['title'];
		$diary->id_category= $id_category;
		$diary->content= $request['content'];
		$diary->id_privacy=$request['privacy_select'];
		$diary->image= "a";
		$diary->save();		
		$image_name="id".$diary->id."user".$user_id;
		
		if($request->hasFile('image')){
			$request->file('image')->move(
				"images",
				"".$image_name.".png"
				);
			$diary->image=$image_name;
			$diary->save();
		}
		else echo "chua co file";
		//check privacy
		if($request['privacy_select']==2){
			$friends= explode("-", $request['danhsach']);
			for($i=1;$i<count($friends);$i++){
				$specificFriend= new SpecificFriend;
				$specificFriend->diary_id= $diary->id;
				$specificFriend->user_id= $friends[$i];
				$specificFriend->save();
			}
			
		}
		return redirect()->back();
		//

	}
	public function listPost(){
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$friendRequests= Friend::where("friend_id",Auth::user()->id)
                            ->where("accepted",0)
                            ->get();
		
		$user= User::where('id',Auth::user()->id)->first();
		//echo $user->diary;
		return view('User.listPost',['diary'=>$user->diary,
			'friendRequests'=>$friendRequests,
			'notifications'=>$notifications]);
	}
	public function viewPost($id){	
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$friendRequests= Friend::where("friend_id",Auth::user()->id)
                            ->where("accepted",0)
                            ->get();	
		$diary= Diary::where("id",$id)->first();
		if($diary->checkPrivacy(Auth::user()->id,$diary)==1)
		{
			$user= User::find(Auth::user()->id);
			//echo $diary;
			$notifications= Notification::where('id_user',Auth::user()->id)
			->where("seen",0)
			->get();
			// xoa thong bao bai viet nay
			if($diary->id_user== Auth::user()->id){
				foreach ($notifications as  $notification) {
					if($notification->id_diary==$diary->id){
						$notification->seen=1;
						$notification->save();
					}
				}
			}
			///
			$friend= $user->friendList;
			$category= $user->category;

			return view('User.postDetail',[
				"diary"=>$diary,
				'friendRequests'=>$friendRequests,
				'notifications'=>$notifications,
				'friends'=>$friend,
				'category'=>$category,
				"comment"=>$diary->comment,
				"user"=>$user
				]);
		}
		else echo "<center><h4>You are no allowed to see this post</h4></center>";
	}
	public function writeComment(Request $request){
		$v = Validator::make($request->all(),[
    		'comment' => 'required'
    	]);
		if($v->fails())
    		return redirect()->back()->withErrors($v->errors());
		$diary= Diary::find($request['diary_id']);
		$comment= new Comment();
		$comment->id_diary= $request['diary_id'];
		$comment->id_user= Auth::user()->id;
		$comment->content=$request['comment'];
		$comment->seen=0;
		$comment->save();
		if(Auth::user()->id!= $diary->id_user){
			$noti= new Notification();
			$noti->id_user=$diary->id_user;
			$noti->id_diary=$diary->id;
			$noti->noti= "<b>".User::find(Auth::user()->id)->name."</b> commented on your post";
			$noti->seen=0;
			$noti->save();
		}
		return redirect()->back();
		
	}
	public function deletePost($id){
		$diary= Diary::find($id);
		$diary->delete();
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$friendRequests= Friend::where("friend_id",Auth::user()->id)
                            ->where("accepted",0)
                            ->get();	
		$user= User::find(Auth::user()->id);
		$diaries= Diary::all();
		$friend= $user->friendList;
		$category= $user->category;
		/*return view('home',['notifications'=>$notifications,
			'friends'=>$friend,
			'friendRequests'=>$friendRequests,
			'category'=>$category,
			'diaries'=>$diaries,
			'user'=>$user
			]);*/
		return redirect()->route('home');
		//return redirect()->back();
	}
	public function editPost($id){
		$diary= Diary::find($id);
		$notifications= Notification::where('id_user',Auth::user()->id)
		->where("seen",0)
		->get();
		$user= User::find(Auth::user()->id);
		$friend= $user->friendList;
		return view('User.editPost',[
			'friendRequests'=>$friendRequests,
			'notifications'=>$notifications,
			'friends'=>$friend,
			'diary'=>$diary,
			'user'=>$user,
			'category'=>Category::all()
			]);
	}
	public function updatePost(Request $request){
		$v = Validator::make($request->all(),[
    		'title' => 'required|max:50',
    		'content' => 'required'
    	]);
		if($v->fails())
    		return redirect()->back()->withErrors($v->errors());
		$user_id=Auth::user()->id;
		$str="".$request['category'];
		$id_category=0;
		if(strlen($str)!=0){
			$category= new Category();
			$category->name= $request['category'];
			$category->id_user=$user_id;
			$category->save();
			$id_category=$category->id;
		}
		else {
			$id_category= $request['category_select'];
		}
		$diary= Diary::find($request['id_post']);
		$diary->title= $request['title'];
		$diary->id_category= $id_category;
		$diary->content= $request['content'];
		$diary->id_privacy=$request['privacy_select'];
		$diary->save();
		$image_name="id".$diary->id."user".$user_id;
		if($request->hasFile('image')){
			$request->file('image')->move(
				"images",
				"".$image_name.".png"
				);
			$diary->image=$image_name;
			$diary->save();	
		}
		if($request['privacy_select']==2){
			$friends= explode("-", $request['danhsach']);
			for($i=1;$i<count($friends);$i++){
				$specificFriend= new SpecificFriend;
				$specificFriend->diary_id= $diary->id;
				$specificFriend->user_id= $friends[$i];
				$specificFriend->save();
			}
			
		}
		return redirect()->back();
	}

	public function getLike($statusId){
        $status = Diary::find($statusId);
        //$user = User::find(Auth::user()->id);
        if(!$status){
            return redirect()->back();
        }

        if(Auth::user()->hasLikedPost($status)){
            return redirect()->back();
        }
        $like = $status->likes()->create([]);
         Auth::user()->likes()->save($like);
        return redirect()->back();
    }
}
