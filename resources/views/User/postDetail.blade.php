
@extends("Layout.index")

@section("content")
<div id = "app">
	<div class="container text-center page-newsfeed-01">    
	  <div class="row page-newsfeed-01-b">
	    @include("User._left_col")
	    @include("User._post_detail")
	    @include("User._right_col")
	    @include("User._editPost")
	  </div>
	</div>
</div>
{{-- @include("User._baimoi") --}}
@endsection

