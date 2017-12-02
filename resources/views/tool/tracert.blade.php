@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <section id="trace">
        <label style="margin: 10px">请输入ip地址或者域名，例如：127.0.0.1或者www.91monitor.com。<code>注意：每次只跟踪5次路由转跳。</code></label>
        <div class="input-group margin" style="width: 500px">

            <input name="ip" type="text" class="form-control" width="500px">
            <input name="_token" type="hidden" value="{!! csrf_token() !!}">
            <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat" v-on:click="ping">查询</button>
        </span>
        </div>
        <pre class="hierarchy bring-up" style="margin: 10px;display: none"><code class="language-bash" data-lang="bash">
        </code></pre>
    </section>
@endsection
@section('js')
    <script>
        var ping = new Vue({
            el: '#trace',
            data: {
            },
            methods: {
                ping:function(){
                    var ip=$(":input[name='ip']").val();
                    var token=$(":input[name='_token']").val();
                    if(!ip){
                        alert("必须填写ip地址");
                        return;
                    }
                    $(".hierarchy").css('display','block');
                    _this=this;
                    _this.$http.post("{{route('tool_trace')}}",{ip:ip,_token:token}).then(function(res){
                        message='';
                        if(res.data.code==='200'){
                            message=res.data.data;
                        }else{
                            message=res.data.msg;
                        }
                        $(".hierarchy").html(message);
                    });
                }
            }
        });

    </script>
@endsection