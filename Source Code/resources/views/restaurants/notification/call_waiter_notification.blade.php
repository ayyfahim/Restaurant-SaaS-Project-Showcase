
<div class="card">
    <div class="modal fade active" id="modal-call-waiter" tabindex="-1" role="dialog" aria-labelledby="modal-notification" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
            <div class="modal-content bg-gradient-info">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-notification">Your attention is required</h6>
                    <button type="button" class="close" id="stopSoundCallwaiter" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="py-3 text-center">
                        <i class="ni ni-bell-55 ni-3x"></i>
                        <h4 class="heading mt-4">NEW WAITER CALL</h4>
                        <p id="table-id"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-white " data-dismiss="modal" id="stopSoundCallwaiter1" >Close</button>
                    <a id="stopSoundCallwaiter3" target="_blank" href="{{route('store_admin.waiter_calls')}}"  class="btn btn-white ml-auto">View Details</a>

                </div>

            </div>
        </div>
    </div>
</div>

<audio id="myAudio">
    <source src="{{ config('app.url') }}/notification/2.mp3" type="audio/ogg">
    <source src="{{ config('app.url') }}/notification/2.mp3" type="audio/mpeg">
</audio>



<script src={{asset("assets/js/lib/jquery.min.js")}}></script>

<script>
    $(function() {
        let notification = document.createElement('audio');
        let notificationFileRoute = '{{asset('notification/3.mp3')}}';
        notification.setAttribute('src', notificationFileRoute);
        notification.setAttribute('type', 'audio/mp3');
        // notification.setAttribute('muted', 'muted');
        notification.setAttribute('loop', 'true');
        $("#stopSoundCallwaiter").click(function(event) {notification.pause();notification.currentTime = 0;});
        $("#stopSoundCallwaiter1").click(function(event) {notification.pause();notification.currentTime = 0;});
        $("#stopSoundCallwaiter2").click(function(event) {
            notification.pause();notification.currentTime = 0;
            $("#modal-call-waiter").modal("hide")
        });
        // notification.play();

        let newOrderCount = null
        setInterval(function() {
            $.ajax({
                url: '{{route('store_admin.new_waiter_calls')}}',
                type: 'GET',
                dataType: 'json',
                data: {listed_order_ids: [], _token: '{{csrf_token()}}'},
            }).done(function(newArray) {
                {{--console.log({{$order_count}},newArray)--}}
                let old_order = newOrderCount || {{$call_waiter_count}};
                if (old_order != newArray.payload.call_waiter_count){
                    newOrderCount = newArray.payload.call_waiter_count;
                    $("#modal-call-waiter").modal("show")
                    document.getElementById("table-id").innerHTML = "Table:"+newArray.payload.waiter_calls[newArray.payload.call_waiter_count-1].table_name
                    console.log(newArray.payload.waiter_calls[newArray.payload.call_waiter_count-1])
                    notification.play();
                }

            })





        },  10*1000)


    })

</script>

