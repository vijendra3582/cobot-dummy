@if(!empty($news) && count($news) > 0)    
   @foreach($news as $key=>$val) 
    <div class="col-12 col-md-4">
        <div class="card h-100 card-default card-secondary card-resources text-center">
            <div class="card-heading">
                <div class="date">{{strtoupper(date('M d, Y', strtotime($val->date))) }}</div>
                <h3>{{ $val->publication }}</h3>
            </div>
            <div class="card-body">
                <p>{{ $val->title }} </p>
            </div>
            <div class="card-footer text-center">
                @if(!empty($val->news_url) && $val->news_type == 'URL')
                    <a class="btn btn-primary ext" data-src="newsPop" href="{{$val->news_url}}" >
                @elseif(!empty($val->news_file) && $val->news_type == 'FILE') 
                    <a class="btn btn-primary" href="{{ route('file-iframe',['type' => 'news','key'=> encrypt(asset(config('constants.news_folder').$val->news_file))]) }}" target="_blank">
                @elseif(!empty($val->video_file) && $val->news_type == 'VIDEO')
                    <a class="btn btn-primary" data-fancybox href="{{asset(config('constants.news_folder').$val->video_file)}}">
                @else
                <a class="btn btn-primary" disabled>
                @endif
                {{ !empty($val->link_title) ? $val->link_title : 'READ MORE' }}</a>  
                <div class="disc-sm">
                    {{ $val->is_disclosure == true ? $val->news_disclosure : '' }}
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif  