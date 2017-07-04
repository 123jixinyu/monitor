@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="col-md-4 col-md-offset-2" id="setting-profile">
        <!-- Profile Image -->
            <div class="box-body box-profile">
                <form action="{{route('upload_avatar')}}" enctype="multipart/form-data" method="post" id="form-avatar">
                    <input id="avatar" type="file" style="display: none" name="avatar" v-on:change="avatar_change"/>
                    {!! csrf_field() !!}
                </form>
                <div class="user-image">
                    <img class="profile-user-img img-responsive img-circle" src="{{user_avatar()}}" alt="User profile picture" v-on:click="click_upload">
                    <h5 class="text-center click-user-img"><a href="javascript:void(0);" v-on:click="click_upload">点击头像修改</a></h5>
                </div>
                <p class="text-muted text-center">{{user_name()}}</p>
                <p class="text-muted text-center">{{Auth::user()->email}}</p>
            </div>
        <div class="tab-pane" id="settings">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">签名</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" placeholder="请输入您的签名" v-model="experience"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">技能</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control"  placeholder="请输入您的技能" v-model="skills">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <span class="btn btn-danger" v-on:click="save_user">保存信息</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var profile=new Vue({
            el:"#setting-profile",
            data:{
                experience:'{{Auth::user()->experience}}',
                skills:'{{Auth::user()->skills}}',
                token:'{{csrf_token()}}'
            },
            methods:{
                save_user:function(){
                    _this=this;
                    _this.$http.post('save_user',{_token:_this.token,experience:_this.experience,skills:_this.skills}).then(function(res){
                        if(res.data.code=='200'){
                            window.location.reload();
                        }else{
                            dialog.show(res.data.msg);
                        }
                    });
                },
                click_upload:function(){
                    $("#avatar").click();
                },
                avatar_change:function(){
                    $('#form-avatar').submit();
                }
            }
        });
    </script>
@endsection