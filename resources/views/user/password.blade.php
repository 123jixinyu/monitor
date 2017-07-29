@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="col-md-4 col-md-offset-2" id="password-setting" style="margin-top: 50px">
        <div class="tab-pane" id="settings">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-3 control-label">原密码</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" placeholder="请输入您的原有密码" v-model="old">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">新密码</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control"  placeholder="请输入您的新密码" v-model="new_pwd">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">重复密码</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control"  placeholder="请重复输入您的新密码" v-model="re_pwd">
                        <input type="hidden" v-model="token">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <span class="btn btn-danger pull-right" v-on:click="save_pwd">保存密码</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- 修改成功后弹窗提示 -->
        <div class="modal fade" tabindex="-1" role="dialog" id="dialog_user">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">密码修改</h4>
              </div>
              <div class="modal-body">
                <p>恭喜您！修改成功！</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" onclick="window.location.reload();">确定</button>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        var pwd=new Vue({
            el:"#password-setting",
            data:{
                old:"",
                new_pwd:'',
                re_pwd:'',
                token:'{{csrf_token()}}'
            },
            methods:{
                save_pwd:function(){
                    _this=this;
                    _this.$http.post("{{route('save_pwd')}}",{old:_this.old,new_pwd:_this.new_pwd,new_pwd_confirmation:_this.re_pwd,_token:this.token}).then(function(res){
                        if(res.data.code=='200'){
                            $("#dialog_user").modal();
                        }else{
                            dialog.show(res.data.msg);
                        }
                    });
                }
            }
        });
    </script>
@endsection
