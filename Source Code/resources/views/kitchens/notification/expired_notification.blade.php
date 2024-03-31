@if($notification!=NULL)

<div class="card">
                <div class="modal fade " id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true" >
                    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                        <div class="modal-content bg-gradient-danger">
                            <div class="modal-header">
                                <h6 class="modal-title" id="modal-title-notification">Your attention is required</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="py-3 text-center">
                                    <i class="ni ni-bell-55 ni-3x"></i>
                                    <h4 class="heading mt-4">{{$notification['head']}}</h4>
                                    <p>{{$notification['sub_head']}}</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="document.getElementById('action-form').submit();" class="btn btn-white">{{$notification['route_submit_button_name']}}</button>
                                <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal" >Close</button>
                            </div>
                            <form method="get" id="action-form" action="{{$notification['route'] !=NULL ?route($notification['route']):NULL}}"/>
                        </div>
                    </div>
                </div>
            </div>
@endif
