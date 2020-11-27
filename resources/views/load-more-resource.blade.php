@if(!empty($resources) && count($resources)>0 )
	@foreach($resources as $key=>$val)    
        <div class="col-12 col-md-4">
            <div class="card card-default card-secondary card-resources text-center">
                <div class="card-heading">
                    <div class="date">{{ strtoupper(date('M d, Y', strtotime($val->date))) }}</div>
                    <h3>{{ $val->title }}</h3>
                </div>
                <div class="card-body">
                    <p>{{ $val->file_type }}</p>
                </div>
                <div class="card-footer text-center">
                    @if(!empty($val->resource_url) && $val->resource_type == 'URL')
                        <a class="btn btn-primary ext" data-src="extPop" href="{{$val->resource_url}}" target="_blank">
                    @elseif(!empty($val->resource_file) && $val->resource_type == 'FILE')
                        <a class="btn btn-primary"  href="{{asset(config('constants.resource_folder'). $val->resource_file)}}" target="_blank">
                    @elseif(!empty($val->video_file) && $val->resource_type == 'VIDEO')
                        <a class="btn btn-primary "  data-fancybox href="{{asset(config('constants.resource_folder').$val->video_file)}}">
                    @else
                        <a class="btn btn-primary">
                    @endif
                            VIEW
                        </a>
                </div>
            </div>
        </div>
    @endforeach
@endif