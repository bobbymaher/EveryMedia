@extends('layouts.app')

@section('content')




    <div class="container col-lg-12 main-content">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ __('My Media') }}

                        <div class="btn-group btn-group-toggle" id="category_group" data-toggle="buttons">
                            <label class="btn btn-secondary active">
                                <input class="category_button" type="radio" name="options" id="category_all"
                                       autocomplete="off" checked> All
                            </label>

                            @foreach($categories as $category)
                                <label class="btn btn-secondary">
                                    <input class="category_button" type="radio" name="options"
                                           id="category_{{$category}}" autocomplete="off"> {{$category}}
                                </label>
                            @endforeach


                        </div>

                        <button type="button" class="btn btn-info pull-right" data-toggle="modal"
                                data-target="#upload_modal">{{ __('Upload file') }}
                        </button>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        <div class="container col-lg-11">
                            <div class="row">
                                @foreach($files as $file)
                                    <div style="width: 18rem;" id="file_div_{{$file->hash}}"
                                         class="card all_media category_all category_{{$file->meta_data['content_type']}}">

                                        @if($file->available && $file->meta_data['content_type'] == 'video')


                                            <a href="#" data-toggle="modal" data-target="#video_modal"
                                               onclick="setVideoModal('{!! $file->url !!}', '{{$file->name}}')">
                                                <div class="play-button-div">
                                                    <img class="card-img-top" src="{{$file->thumbnail}}"
                                                         alt="{{$file->name}} thumbnail">
                                                    <span class="play-button-icon"><i class="fa fa-play"
                                                                                      aria-hidden="true"></i></span>
                                                </div>
                                            </a>

                                        @elseif($file->available && $file->meta_data['content_type'] == 'audio')


                                            <a href="#!" onclick="playAudio('{{$file->name}}', '{!! $file->url !!}')">
                                                <img class="card-img-top" src="{{$file->thumbnail}}"
                                                     alt="{{$file->name}} download">
                                            </a>
                                        @else
                                            <a target="_{{$file->hash}}" href="{!! $file->url !!}">
                                                <img class="card-img-top" src="{{$file->thumbnail}}"
                                                     alt="{{$file->name}} download">
                                            </a>

                                        @endif

                                        <div class="card-body">
                                            <h5 class="card-title" id="title_{{$file->hash}}">{{$file->name}}<p
                                                    class="light_file_extension"> {{$file->meta_data['content_type']}}</p>
                                            </h5>
                                            <p class="card-text">{{\App\Http\Controllers\FileController::formatSizeUnits($file->meta_data['size'])}}</p>


                                            @if(!$file->available)
                                                <p class="card-text"> {{ __('File is being processed, check back in a min') }}</p>
                                            @else
                                                <a target="_{{$file->hash}}" href="{!! $file->url !!}"
                                                   class="btn btn-primary"> <i class="fa fa-download"></i></a>
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="deleteFile('{{$file->hash}}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button type="submit" class="btn btn-info"
                                                        onclick="renameFile('{{$file->name}}', '{{$file->hash}}')">
                                                    <i class="fa fa-pencil"></i>
                                                </button>


                                                @switch($file->meta_data['content_type'])
                                                    @case('video')

                                                    @break

                                                    @case('audio')


                                                    <button type="submit" class="btn btn-success"
                                                            onclick="playAudio('{{$file->name}}', '{!! $file->url !!}')">
                                                        <i class="fa fa-play"></i>
                                                    </button>


                                                    @break

                                                    @case('image')
                                                <!--  <img src="{!! $file->url !!}" width="100%" /> -->
                                                    <a target="_{{$file->hash}}" href="{!! $file->url !!}"
                                                       class="btn btn-primary"> <i class="fa fa-external-link"></i></a>
                                                    @break

                                                    @case('other')
                                                    <p class="card-subtitle">{{ __('Unsupported File') }}
                                                        '{{$file->meta_data['extension']}}
                                                        ' {{ __('You can still attempt to open/download the file')}}</p>

                                                    @break

                                                    @default
                                                    <a target="_{{$file->hash}}" href="{!! $file->url !!}"
                                                       class="btn btn-primary">{{ __('[err] Unsupported file') }}
                                                        '{{$file->meta_data['extension']}}'</a>
                                                @endswitch



                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- footer , mostly used for media controls -->

    <footer id="audio_footer" class="footer fixed-bottom" style="display: none">
        <div class="container  col-lg-10">
            <div class="row">

                <div class="col-lg-4">
                    <h1 id="audio_now_playing">None</h1>
                </div>

                <div class="col-lg-8">
                    <audio id="audio_control" controls>
                        <source id="audio_src" src=""
                                type="">
                        {{ __('Your browser does not support the audio element.') }}
                    </audio>
                </div>

            </div>

        </div>
    </footer>

    <!-- the video popup box -->

    <div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog large-video-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="video_modal_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>

                </div>
                <div class="modal-body">
                    <video controls id="video_player" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
                        <source id="video_player_src" src="" type="video/mp4">
                        {{ __('Your browser doesn\'t support HTML5 video tag booo') }}
                    </video>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>


    <!-- the upload popup box -->

    <div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog large-video-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <p>Drag and drop multiple files at once</p>
                        <div class="col-lg-6">

                            <form id='file-catcher' action="{{ route('file.upload.post') }}">
                                <input id='file-input' method="POST"
                                       type='file' multiple/>
                                <button type='submit'>
                                    Upload files
                                </button>
                            </form>

                            <div id='file-list-display'></div>
                            <div class="col-lg-6">
                                <div id="upload_status" class="col-md-6">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>


        <script>
            var file_delete_url = '{{route('file.delete')}}';
            var file_rename_url = '{{route('file.rename')}}';
        </script>
        <script src="/js/home.js?version={{ env('VERSION', '0.0') }}"></script>
        <script src="/js/filehandler.js?version={{ env('VERSION', '0.0') }}"></script>

@endsection
